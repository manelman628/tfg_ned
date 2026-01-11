<?php

namespace App\Entities;

use Carbon\Carbon;
use CodeIgniter\Entity\Entity;

class Aviso extends Entity
{
   
    public function setFechaAviso($dataA = null)
    {
      if($dataA == null){
        $this->attributes['fecha_aviso'] = Carbon::now()->toDateString();
      }else{
        $this->attributes['fecha_aviso'] = Carbon::parse($dataA)->toDateString();
      }

      return $this;
    }

    public function getFechaAvisoPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_aviso'], 'Y-m-d')){
         return Carbon::parse($this->attributes['fecha_aviso'])->format('d/m/Y');
       }
       return $this->attributes['fecha_aviso'];
    }

    public function setMensaje($logCambios = null)
    {
      if($logCambios == null){
        $this->attributes['mensaje'] = '';
      }else{
        $this->attributes['mensaje'] = $logCambios;
      }
      return $this;
    }

    public function setProcesado()
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_proceso'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_proceso'] = 'ned';
      }
      $this->attributes['fecha_proceso'] = Carbon::now()->toDateTimeString();
      $this->attributes['procesado'] = 1;
      return $this;
    }

}
