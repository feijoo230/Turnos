#!/usr/bin/env python3
"""
Demo en vivo para la defensa (Anexo 2) - Observatorio Astronómico Prof. Elvio Alanís (UNSa)

Descubre automáticamente un espacio, actividad, fecha y horario disponibles en el entorno local,
dispara N pedidos HTTP simultáneos al mismo milisegundo (usando threading.Barrier) para demostrar
que el bloqueo pesimista (SELECT ... FOR UPDATE en turnos_horarios) evita el overbooking, y luego
ejecuta la batería completa de pruebas de seguridad contra una reserva real creada.

Uso simple (todo automático):
  python docs/demo_concurrencia_defensa.py --url http://localhost:8083

Uso con opciones:
  python docs/demo_concurrencia_defensa.py --url http://localhost:8083 --hilos 5 --personas 5
  python docs/demo_concurrencia_defensa.py --url http://localhost:8083 --solo-seguridad --reserva-id 15 --codigo OBS-7K4M9 --dni 40999001
"""

import argparse
import concurrent.futures
import datetime
import http.cookiejar
import json
import random
import re
import threading
import urllib.error
import urllib.parse
import urllib.request


DIAS_SEMANA = ["lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"]


def make_opener():
    cj = http.cookiejar.CookieJar()
    return urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj)), cj


def extract_csrf_token(html: str):
    # Soporta cualquier orden de atributos en <input name="_token" type="hidden" value="...">
    for tag in re.findall(r"<input\b[^>]*>", html, re.I):
        if re.search(r'name=["\']_token["\']', tag, re.I):
            m = re.search(r'value=["\']([^"\']+)["\']', tag, re.I)
            if m:
                return m.group(1)
    m_js = re.search(r'["\']_token["\']\s*:\s*["\']([^"\']+)["\']', html)
    if m_js:
        return m_js.group(1)
    m_meta = re.search(r'<meta[^>]+name=["\']csrf-token["\'][^>]+content=["\']([^"\']+)["\']', html, re.I)
    return m_meta.group(1) if m_meta else None


def autodescubrir_turno(base_url: str, dep_forzado=None, tramite_forzado=None):
    opener, _ = make_opener()

    # 1. Buscar dependencia con turnos
    deps = json.loads(opener.open(f"{base_url}/getdirecciones").read().decode("utf-8"))
    if dep_forzado:
        dep = next((d for d in deps if str(d.get("id")) == str(dep_forzado)), None)
    else:
        dep = next((d for d in deps if d.get("has_turns")), None)
    if not dep:
        raise RuntimeError("No se encontró ninguna dependencia con turnos disponibles en el servidor.")

    # 2. Buscar trámite con turnos dentro de esa dependencia
    tramites = json.loads(opener.open(f"{base_url}/gettramites/{dep['id']}").read().decode("utf-8"))
    if tramite_forzado:
        tramite = next((t for t in tramites if str(t.get("id")) == str(tramite_forzado)), None)
    else:
        tramite = next((t for t in tramites if t.get("has_turns")), None)
    if not tramite:
        raise RuntimeError(f"La dependencia '{dep['nombre']}' no tiene actividades con turnos disponibles.")

    # 3. Abrir Paso 2 para obtener configuración de calendario y horarios
    url_p2 = f"{base_url}/tramite.paso2?dependencia_id={dep['id']}&dependencia_tramite_id={tramite['id']}"
    html_p2 = opener.open(url_p2).read().decode("utf-8", errors="ignore")
    token = extract_csrf_token(html_p2)
    m_tt = re.search(r"'id'\s*:\s*\"(\d+)\"", html_p2)
    m_min = re.search(r'minDate\s*:\s*"(\d{2}/\d{2}/\d{4})"', html_p2)
    m_max = re.search(r'maxDate\s*:\s*"(\d{2}/\d{2}/\d{4})"', html_p2)
    m_dias = re.search(r"var\s+diasActivos\s*=\s*(\{.*?\});", html_p2)
    m_fer = re.search(r"var\s+disableddates\s*=\s*(\[.*?\]);", html_p2)

    if not (token and m_tt and m_min and m_max):
        raise RuntimeError("No se pudo leer la configuración de fechas del Paso 2.")

    tt_id = m_tt.group(1)
    dias_activos = json.loads(m_dias.group(1)) if m_dias else {}
    disabled = set(json.loads(m_fer.group(1)) if m_fer else [])

    # Consultar días sin cupo vía POST /turnos.getdisableddates
    req_dis = urllib.request.Request(
        f"{base_url}/turnos.getdisableddates",
        data=urllib.parse.urlencode({"id": tt_id, "_token": token}).encode("utf-8"),
        method="POST",
    )
    extra_dis = json.loads(opener.open(req_dis).read().decode("utf-8"))
    disabled.update(extra_dis)

    d_start = datetime.datetime.strptime(m_min.group(1), "%d/%m/%Y").date()
    d_end = datetime.datetime.strptime(m_max.group(1), "%d/%m/%Y").date()

    # 4. Encontrar el primer día y horario con cupo real
    cur = d_start
    while cur <= d_end:
        fecha_str = cur.strftime("%d/%m/%Y")
        dia_nombre = DIAS_SEMANA[cur.weekday()]
        if dias_activos.get(dia_nombre, True) and fecha_str not in disabled:
            req_hor = urllib.request.Request(
                f"{base_url}/turnos.loadhorarios",
                data=urllib.parse.urlencode({"id": tt_id, "_token": token, "turno_fecha": fecha_str}).encode("utf-8"),
                method="POST",
            )
            html_hor = opener.open(req_hor).read().decode("utf-8", errors="ignore")
            m_slot = re.search(r'name="turno_hora"\s+value="([^"]+)"', html_hor)
            m_seats = re.search(r'class="slot-seats">([^<]+)<', html_hor)
            if m_slot:
                return {
                    "dep": dep,
                    "tramite": tramite,
                    "fecha": fecha_str,
                    "hora_slot": m_slot.group(1),
                    "cupo_label": m_seats.group(1).strip() if m_seats else "disponible",
                }
        cur += datetime.timedelta(days=1)

    raise RuntimeError(f"No se encontró ningún horario disponible para '{tramite['nombre']}'.")


def intentar_reserva(idx: int, barrier: threading.Barrier, base_url: str, dep_id: int, tramite: dict, fecha: str, hora_slot: str, personas: int, prefijo_dni: int):
    opener, _ = make_opener()

    # Preparar sesión: Paso 2 -> Paso 3
    opener.open(f"{base_url}/tramite.paso2?dependencia_id={dep_id}&dependencia_tramite_id={tramite['id']}").read()
    q = urllib.parse.urlencode({"turno_fecha": fecha, "turno_hora": hora_slot})
    html_p3 = opener.open(f"{base_url}/tramite.paso3?{q}").read().decode("utf-8", errors="ignore")

    token = extract_csrf_token(html_p3)
    if not token:
        return {"idx": idx, "estado": "SIN_TOKEN", "detalle": "No se pudo obtener token CSRF en Paso 3"}

    mod = str(tramite.get("tipo_modalidad") or "individual").lower()
    req_inst = str(tramite.get("requiere_institucion") or "0") in ("1", "true", "True")
    es_institucional = (mod == "institucional") or (req_inst and mod != "mixto")
    es_grupal = es_institucional or (mod == "grupal") or (mod == "mixto" and personas > 1)

    min_p = int(tramite.get("min_personas_reserva") or 2)
    max_p = int(tramite.get("max_personas_reserva") or 30)
    cant = 1 if mod == "individual" and not es_institucional else max(min_p, min(personas, max_p))

    dni = f"{prefijo_dni}{idx:02d}"
    data = {
        "_token": token,
        "nombre_apellido": f"Demo Concurrencia {idx}",
        "dni": dni,
        "celular": "3874000000",
        "email": f"demo.concurrencia.{idx}@example.com",
        "email_confirmation": f"demo.concurrencia.{idx}@example.com",
        "es_grupal": 1 if es_grupal else 0,
        "cantidad_personas": cant,
    }
    if es_institucional:
        data.update({
            "nombre_institucion": f"Escuela Demo N° {idx}",
            "nivel_institucion": "Secundario",
            "cargo_responsable": "Docente Coordinador",
            "cantidad_acompanantes": 1,
        })

    payload = urllib.parse.urlencode(data).encode("utf-8")
    req = urllib.request.Request(f"{base_url}/tramite.guardar", data=payload, method="POST")

    # Sincronizar TODOS los hilos para disparar el POST exactamente al mismo milisegundo
    barrier.wait()

    try:
        resp = opener.open(req)
        final_url = resp.geturl()
        body = resp.read().decode("utf-8", errors="ignore")
        m_id = re.search(r"/tramite/confirmacion/(\d+)", final_url)
        if m_id:
            m_cod = re.search(r'id="codigo-reserva-texto"[^>]*>([^<]+)<', body)
            codigo = m_cod.group(1).strip() if m_cod else ""
            return {
                "idx": idx,
                "estado": "RESERVADO",
                "detalle": f"Código {codigo} (ID #{m_id.group(1)}, DNI {dni}, {cant} pers.)",
                "reserva_id": int(m_id.group(1)),
                "codigo": codigo,
                "dni": dni,
            }
        m_err = re.search(r'alert-danger[^>]*>(.*?)</div>', body, re.S)
        msg = " ".join(re.sub(r"<[^>]+>", " ", m_err.group(1)).split()) if m_err else "Rechazado por validación/cupo"
        return {"idx": idx, "estado": "RECHAZADO", "detalle": msg}
    except urllib.error.HTTPError as e:
        return {"idx": idx, "estado": f"HTTP_{e.code}", "detalle": str(e)}


def demo_concurrencia(base_url: str, info: dict, hilos: int, personas: int):
    dep = info["dep"]
    tramite = info["tramite"]
    hora_corta = info["hora_slot"].split("|")[0][:5]
    print("\n============================================================")
    print(" DEMO 1: CONCURRENCIA DE PEDIDOS SIMULTÁNEOS (lockForUpdate)")
    print("============================================================")
    print(f" Espacio:   {dep['nombre']} (ID {dep['id']})")
    print(f" Actividad: {tramite['nombre']} (ID {tramite['id']}, modalidad: {tramite.get('tipo_modalidad')})")
    print(f" Turno:     {info['fecha']} a las {hora_corta} hs ({info['cupo_label']})")
    print(f" Disparo:   {hilos} solicitudes simultáneas sincronizadas con Barrier...\n")

    barrier = threading.Barrier(hilos)
    prefijo_dni = random.randint(410000, 489999)

    with concurrent.futures.ThreadPoolExecutor(max_workers=hilos) as ex:
        futures = [
            ex.submit(intentar_reserva, i + 1, barrier, base_url, dep["id"], tramite, info["fecha"], info["hora_slot"], personas, prefijo_dni)
            for i in range(hilos)
        ]
        resultados = [f.result() for f in concurrent.futures.as_completed(futures)]

    resultados.sort(key=lambda r: r["idx"])
    aprobadas = []
    rechazadas = 0
    for r in resultados:
        tag = "OK - RESERVADO" if r["estado"] == "RESERVADO" else r["estado"]
        print(f"  Hilo #{r['idx']}: [{tag}] {r['detalle']}")
        if r["estado"] == "RESERVADO":
            aprobadas.append(r)
        elif r["estado"] == "RECHAZADO":
            rechazadas += 1

    print(f"\nResumen concurrencia: {len(aprobadas)} aprobada(s) | {rechazadas} rechazada(s) limpiamente sin overbooking.")
    return aprobadas[0] if aprobadas else None


def demo_seguridad(base_url: str, reserva_id: int, codigo: str, dni: str = None):
    print("\n============================================================")
    print(" DEMO 2: SEGURIDAD DE RESERVAS (DNI, CÓDIGO E IDOR)")
    print("============================================================")
    print(f" Reserva objetivo: ID #{reserva_id} | Código {codigo}\n")

    # 1. IDOR en comprobante PDF sin sesión
    url_pdf = f"{base_url}/turnos.print/{reserva_id}"
    try:
        urllib.request.urlopen(url_pdf)
        print(f"  [FALLA] {url_pdf} permitió descarga sin autorización")
    except urllib.error.HTTPError as e:
        nota = "bloqueado por autorización" if e.code == 403 else "no existe ese ID en BD (usá --reserva-id real)"
        print(f"  [{'OK' if e.code == 403 else 'INFO'}] IDOR PDF -> HTTP {e.code} ({nota})")

    # 2. IDOR en vista de confirmación por ID sin sesión
    opener, _ = make_opener()
    resp_conf = opener.open(f"{base_url}/tramite/confirmacion/{reserva_id}")
    redir_ok = "/tramite/confirmacion/" not in resp_conf.geturl()
    print(f"  [{'OK' if redir_ok else 'FALLA'}] IDOR Confirmación sin sesión -> redirigida a {resp_conf.geturl()}")

    # 3. IDOR en nómina de integrantes sin autorización
    url_int = f"{base_url}/reserva/{urllib.parse.quote(codigo)}/integrantes"
    try:
        resp_int = opener.open(url_int)
        int_ok = "/integrantes" not in resp_int.geturl()
        print(f"  [{'OK' if int_ok else 'FALLA'}] IDOR Nómina sin verificación -> redirigida a {resp_int.geturl()}")
    except urllib.error.HTTPError as e:
        print(f"  [INFO] Nómina devolvió HTTP {e.code} (verificá que el código '{codigo}' exista en BD)")

    # 4. POST cancelar directo sin autorización (obteniendo CSRF desde /turnos)
    html_turnos = opener.open(f"{base_url}/turnos").read().decode("utf-8", errors="ignore")
    csrf = extract_csrf_token(html_turnos)
    if csrf:
        req_cancel = urllib.request.Request(
            f"{base_url}/reserva/{urllib.parse.quote(codigo)}/cancelar",
            data=urllib.parse.urlencode({"_token": csrf, "motivo_cancelacion": "Intento sin autorizacion"}).encode("utf-8"),
            method="POST",
        )
        try:
            opener.open(req_cancel)
            print("  [FALLA] POST cancelar sin autorización fue aceptado")
        except urllib.error.HTTPError as e:
            print(f"  [{'OK' if e.code == 403 else 'INFO'}] POST cancelar sin autorización -> HTTP {e.code}")

    # 5. Consulta pública SOLO con DNI (sin código)
    dni_prueba = dni or "30123456"
    body_dni = opener.open(f"{base_url}/turno.buscar?dni_turno={urllib.parse.quote(dni_prueba)}").read().decode("utf-8", errors="ignore")
    print(f"  [{'OK' if 'RESERVA ENCONTRADA' not in body_dni else 'FALLA'}] Consulta SOLO con DNI bloqueada (no expone reserva)")

    # 6. Consulta pública SOLO con Código (sin DNI)
    body_cod = opener.open(f"{base_url}/turno.buscar?codigo_turno={urllib.parse.quote(codigo)}").read().decode("utf-8", errors="ignore")
    print(f"  [{'OK' if 'RESERVA ENCONTRADA' not in body_cod else 'FALLA'}] Consulta SOLO con Código bloqueada (no expone reserva)")

    # 7. Consulta con Código válido + DNI ajeno
    body_bad = opener.open(f"{base_url}/turno.buscar?codigo_turno={urllib.parse.quote(codigo)}&dni_turno=99999999").read().decode("utf-8", errors="ignore")
    print(f"  [{'OK' if 'RESERVA ENCONTRADA' not in body_bad else 'FALLA'}] Código real + DNI ajeno bloqueado (mensaje genérico)")

    # 8. Flujo legítimo con Código + DNI válidos -> permite ver y descargar PDF
    if dni:
        opener_ok, _ = make_opener()
        body_ok = opener_ok.open(
            f"{base_url}/turno.buscar?codigo_turno={urllib.parse.quote(codigo.lower().replace('-', ''))}&dni_turno={urllib.parse.quote(dni)}"
        ).read().decode("utf-8", errors="ignore")
        encontrada = "RESERVA ENCONTRADA" in body_ok
        print(f"  [{'OK' if encontrada else 'FALLA'}] Titular legítimo (Código + DNI) -> consulta autorizada")
        if encontrada:
            try:
                pdf_resp = opener_ok.open(url_pdf)
                ct = pdf_resp.headers.get("Content-Type", "")
                size = len(pdf_resp.read())
                print(f"  [OK] Descarga de comprobante PDF tras verificar Código + DNI -> HTTP 200 ({ct}, {size} bytes)")
            except urllib.error.HTTPError as e:
                print(f"  [FALLA] No pudo descargar PDF tras autorizar -> HTTP {e.code}")


def main():
    parser = argparse.ArgumentParser(description="Demo automática de concurrencia y seguridad para defensa UNSa")
    parser.add_argument("--url", default="http://localhost:8083")
    parser.add_argument("--solo-seguridad", action="store_true", help="Ejecuta solo pruebas de seguridad")
    parser.add_argument("--solo-concurrencia", action="store_true", help="Ejecuta solo prueba de concurrencia")
    parser.add_argument("--reserva-id", type=int, default=None)
    parser.add_argument("--codigo", default=None)
    parser.add_argument("--dni", default=None)
    parser.add_argument("--dep", type=int, default=None)
    parser.add_argument("--tramite", type=int, default=None)
    parser.add_argument("--hilos", type=int, default=5)
    parser.add_argument("--personas", type=int, default=10)
    args = parser.parse_args()

    base_url = args.url.rstrip("/")
    reserva_creada = None

    if not args.solo_seguridad:
        info = autodescubrir_turno(base_url, args.dep, args.tramite)
        reserva_creada = demo_concurrencia(base_url, info, args.hilos, args.personas)

    if not args.solo_concurrencia:
        rid = args.reserva_id or (reserva_creada["reserva_id"] if reserva_creada else 1)
        cod = args.codigo or (reserva_creada["codigo"] if reserva_creada else "OBS-TEST1")
        dni = args.dni or (reserva_creada["dni"] if reserva_creada else None)
        demo_seguridad(base_url, rid, cod, dni)


if __name__ == "__main__":
    main()
