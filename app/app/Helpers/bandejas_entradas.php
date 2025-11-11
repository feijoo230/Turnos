<?php
  function bandejas_entradas(){
    
    $cant_nuevos = App\Models\Resolucion::where('resolucion_estado_id', 1)->count();

    return $cant_nuevos;
  }