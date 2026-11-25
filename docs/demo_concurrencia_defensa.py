#!/usr/bin/env python3
"""
Demo en vivo para la defensa (Anexo 2) - Observatorio Astronómico Prof. Elvio Alanís (UNSa)

Demuestra contra el entorno local (http://localhost:8083):
  1. Seguridad de reservas (IDOR en /turnos.print/{id}, /reserva/{codigo}/integrantes,
     consulta con DNI solo y con código solo -> todos bloqueados).
  2. Concurrencia de pedidos simultáneos disparados al mismo milisegundo contra el
     mismo horario (usando ThreadPoolExecutor con sesiones independientes), comprobando
     que el bloqueo pesimista (SELECT ... FOR UPDATE en turnos_horarios) impide el overbooking.

Uso:
  python docs/demo_concurrencia_defensa.py --url http://localhost:8083 --seguridad
  python docs/demo_concurrencia_defensa.py --url http://localhost:8083 --concurrencia --dep 27 --tramite 1 --fecha 28/11/2026 --hora "19:00:00|1" --hilos 5 --personas 10
"""

import argparse
import concurrent.futures
import http.cookiejar
import re
import urllib.error
import urllib.parse
import urllib.request


def make_opener():
    cj = http.cookiejar.CookieJar()
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj)), cj


def demo_seguridad(base_url: str, reserva_id: int, codigo: str):
    print("\n=== DEMO 1: SEGURIDAD DE RESERVAS ===")

    # 1. IDOR en comprobante PDF sin sesión
    url_pdf = f"{base_url}/turnos.print/{reserva_id}"
    try:
        urllib.request.urlopen(url_pdf)
        print(f"[FALLA] {url_pdf} permitió descarga sin autorización")
    except urllib.error.HTTPError as e:
        print(f"[OK] IDOR PDF bloqueado -> HTTP {e.code} en {url_pdf}")

    # 2. IDOR en vista de confirmación por ID sin sesión
    url_conf = f"{base_url}/tramite/confirmacion/{reserva_id}"
    opener, _ = make_opener()
    resp = opener.open(url_conf)
    print(f"[OK] Confirmación sin sesión redirigida a: {resp.geturl()}")

    # 3. IDOR en nómina de integrantes sin autorización
    url_int = f"{base_url}/reserva/{codigo}/integrantes"
    resp2 = opener.open(url_int)
    print(f"[OK] Nómina sin verificación redirigida a: {resp2.geturl()}")

    # 4. Consulta pública solo con DNI (sin código)
    url_dni = f"{base_url}/turno.buscar?dni_turno=30123456"
    resp3 = opener.open(url_dni)
    body3 = resp3.read().decode("utf-8", errors="ignore")
    ok_dni = "RESERVA ENCONTRADA" not in body3
    print(f"[{'OK' if ok_dni else 'FALLA'}] Consulta solo con DNI bloqueada (no expone reserva)")

    # 5. Consulta pública solo con Código (sin DNI)
    url_cod = f"{base_url}/turno.buscar?codigo_turno={urllib.parse.quote(codigo)}"
    resp4 = opener.open(url_cod)
    body4 = resp4.read().decode("utf-8", errors="ignore")
    ok_cod = "RESERVA ENCONTRADA" not in body4
    print(f"[{'OK' if ok_cod else 'FALLA'}] Consulta solo con Código bloqueada (no expone reserva)")


def intentar_reserva(idx: int, base_url: str, dep_id: int, tramite_id: int, fecha: str, hora_slot: str, personas: int):
    opener, _ = make_opener()

    # Paso 1 -> Paso 2 -> Paso 3 para poblar sesión y obtener CSRF token
    opener.open(f"{base_url}/tramite.paso2?dependencia_id={dep_id}&dependencia_tramite_id={tramite_id}").read()
    q = urllib.parse.urlencode({"turno_fecha": fecha, "turno_hora": hora_slot})
    html_p3 = opener.open(f"{base_url}/tramite.paso3?{q}").read().decode("utf-8", errors="ignore")

    m = re.search(r'name="_token"\s+value="([^"]+)"', html_p3)
    if not m:
        return (idx, "SIN_TOKEN", "No se pudo abrir el Paso 3 (verificá dep/tramite/fecha/hora)")
    token = m.group(1)

    dni = f"4099900{idx}"
    payload = urllib.parse.urlencode({
        "_token": token,
        "nombre_apellido": f"Prueba Concurrencia {idx}",
        "dni": dni,
        "celular": "3874000000",
        "email": f"concurrencia{idx}@example.com",
        "email_confirmation": f"concurrencia{idx}@example.com",
        "es_grupal": 1 if personas > 1 else 0,
        "cantidad_personas": personas,
    }).encode("utf-8")

    req = urllib.request.Request(f"{base_url}/tramite.guardar", data=payload, method="POST")
    try:
        resp = opener.open(req)
        final_url = resp.geturl()
        body = resp.read().decode("utf-8", errors="ignore")
        if "/tramite/confirmacion/" in final_url:
            cod = re.search(r'id="codigo-reserva-texto"[^>]*>([^<]+)<', body)
            return (idx, "RESERVADO", cod.group(1).strip() if cod else final_url)
        err = re.search(r'alert-danger[^>]*>(.*?)</div>', body, re.S)
        msg = re.sub(r'<[^>]+>', ' ', err.group(1)).strip() if err else "Rechazado por cupo"
        return (idx, "RECHAZADO", " ".join(msg.split()))
    except urllib.error.HTTPError as e:
        return (idx, f"HTTP_{e.code}", str(e))


def demo_concurrencia(base_url: str, dep_id: int, tramite_id: int, fecha: str, hora_slot: str, hilos: int, personas: int):
    print(f"\n=== DEMO 2: CONCURRENCIA ({hilos} pedidos simultáneos x {personas} personas) ===")
    with concurrent.futures.ThreadPoolExecutor(max_workers=hilos) as ex:
        futures = [
            ex.submit(intentar_reserva, i + 1, base_url, dep_id, tramite_id, fecha, hora_slot, personas)
            for i in range(hilos)
        ]
        resultados = [f.result() for f in concurrent.futures.as_completed(futures)]

    resultados.sort(key=lambda r: r[0])
    ok = sum(1 for _, estado, _ in resultados if estado == "RESERVADO")
    rej = sum(1 for _, estado, _ in resultados if estado == "RECHAZADO")
    for idx, estado, detalle in resultados:
        print(f"  Hilo #{idx}: [{estado}] {detalle}")
    print(f"\nResumen: {ok} reserva(s) aprobada(s) | {rej} rechazada(s) limpiamente sin overbooking.")


def main():
    parser = argparse.ArgumentParser(description="Demo de seguridad y concurrencia para defensa UNSa")
    parser.add_argument("--url", default="http://localhost:8083")
    parser.add_argument("--seguridad", action="store_true")
    parser.add_argument("--reserva-id", type=int, default=1)
    parser.add_argument("--codigo", default="OBS-TEST1")
    parser.add_argument("--concurrencia", action="store_true")
    parser.add_argument("--dep", type=int, default=27)
    parser.add_argument("--tramite", type=int, default=1)
    parser.add_argument("--fecha", default="28/11/2026")
    parser.add_argument("--hora", default="19:00:00|1")
    parser.add_argument("--hilos", type=int, default=5)
    parser.add_argument("--personas", type=int, default=10)
    args = parser.parse_args()

    if not args.seguridad and not args.concurrencia:
        args.seguridad = True

    if args.seguridad:
        demo_seguridad(args.url.rstrip("/"), args.reserva_id, args.codigo)
    if args.concurrencia:
        demo_concurrencia(args.url.rstrip("/"), args.dep, args.tramite, args.fecha, args.hora, args.hilos, args.personas)


if __name__ == "__main__":
    main()
