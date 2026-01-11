<?php

namespace App\Entities;

use Carbon\Carbon;
use CodeIgniter\Entity\Entity;

class Tratamiento extends Entity
{
   

    public function getFechaIngresoPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_ingreso'], 'Y-m-d')){
         $this->attributes['fecha_ingreso'] = Carbon::parse($this->attributes['fecha_ingreso'])->format('d/m/Y');
       }
       return $this->attributes['fecha_ingreso'];
    }

    public function getFechaAltaPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_alta'], 'Y-m-d')){
         $this->attributes['fecha_alta'] = Carbon::parse($this->attributes['fecha_alta'])->format('d/m/Y');
       }
       return $this->attributes['fecha_alta'];
    }

    public function getConfirmadoHastaPretty()
    {
      if(Carbon::hasFormat($this->attributes['confirmado_hasta'], 'Y-m-d H:i:s')){
         $this->attributes['confirmado_hasta'] = Carbon::parse($this->attributes['confirmado_hasta'])->format('d/m/Y');
       }
       return $this->attributes['confirmado_hasta'];
    }

      
    public function getCanvioConfirmadoPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_cambio_confirmado'], 'Y-m-d')){
         $this->attributes['fecha_cambio_confirmado'] = Carbon::parse($this->attributes['fecha_cambio_confirmado'])->format('d/m/Y');
       }
       return $this->attributes['fecha_cambio_confirmado'];
    }

    

}
