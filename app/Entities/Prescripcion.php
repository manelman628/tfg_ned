<?php

namespace App\Entities;

use Carbon\Carbon;
use CodeIgniter\Entity\Entity;

class Prescripcion extends Entity
{
   

    public function getFechaInicioPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_inicio'], 'Y-m-d')){
         $this->attributes['fecha_inicio'] = Carbon::parse($this->attributes['fecha_inicio'])->format('d/m/Y');
       }
       return $this->attributes['fecha_inicio'];
    }
  
    public function getFechaFinPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_fin'], 'Y-m-d')){
         $this->attributes['fecha_fin'] = Carbon::parse($this->attributes['fecha_fin'])->format('d/m/Y');
       }
       return $this->attributes['fecha_fin'];
    }
  

}
