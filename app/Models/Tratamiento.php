<?php

namespace App\Models;

use CodeIgniter\Model;
use Carbon\Carbon;

class Tratamiento extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tratamientos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'App\Entities\Tratamiento';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
      'id',
      'fecha_ingreso',
      'fecha_alta',
      'suspendido',
      'confirmado_hasta',
      'pendiente_confirmar',
      'valido_hasta',
      'etc_id',
      'fecha_proceso',
      'NHC', 
      'cip', 
      'nif', 
      'nombre_paciente', 
      'apellidos_paciente',  
      'fecha_nacimiento',   
      'fecha_exitus',
      'direccion', 
      'poblacion', 
      'codigo_postal', 
      'provincia', 
      'nacionalidad', 
      'telefono', 
      'sexo',
      'usuario', 
      'usuario_login', 
      'usuario_grupo', 
      'usuario_servicio',
      'usuario_rol', 
      'usuario_num_colegiado', 
      'usuario_email', 
      'usuario_codigo_medico', 
      'usuario_activo', 
      'servicio',
      'servicio_codigo',
      'obs_tratamiento',
      'estado_registro'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_modificacion';
    protected $deletedField  = 'fecha_eliminacion';

    // Validation
    protected $validationRules = [];
    protected $validationMessages   = [];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected $estadoInicial    = 'Pendiente';
    protected $boostrapEstadoInicial    = 'secondary';

      /**
       * Lista los tratamientos de la tabla, admite filtros por valor, por like, y otros donde van las sentencias del where en un array
       * @return array
       */
      public function findAllWithFilters($arrayWhere = null, $arrayLike = null, $arrayDates = [], $arrayBetweenDates = [], $arrayServeis = null)
      {
        $tratamientos = $this
                ->select(["tratamientos.*", "solicitudes.fecha_cambio_confirmado",
                  "CONCAT(tratamientos.nombre_paciente, ' ') AS nombre_completo",
                  "IF(confirmado_hasta IS NULL OR confirmado_hasta >='".Carbon::now()->toDateString()."',0,1) AS caducat",
                  "IFNULL(estados.id, 1) as estado_id",
                  "IFNULL(estados.descripcion,'" . $this->estadoInicial . "') as estado", 
                  "IFNULL(estados.clase_bootstrap,'" . $this->boostrapEstadoInicial . "') as bootstrap_class",
                  "GROUP_CONCAT(DISTINCT CONCAT(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.codigo_indicacion, null), ': ', IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.indicacion, null)) SEPARATOR '; ') AS prescripciones_indicacion",
                  "GROUP_CONCAT(DISTINCT CONCAT(prescripciones.codigo_indicacion, ': ', prescripciones.indicacion) SEPARATOR '; ') AS prescripciones_indicacion_totales",
                  "COUNT(prescripciones.id_prescripcion) AS prescripciones_totales",
                  "SUM(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."',0,1)) AS prescripciones_caducadas",
                  "GROUP_CONCAT(DISTINCT IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.usuario_prescripcion, null) SEPARATOR '; ') AS prescripciones_prescriptores",
                  "GROUP_CONCAT(DISTINCT prescripciones.usuario_prescripcion SEPARATOR '; ') AS prescripciones_prescriptores_totales"],FALSE)
                ->join('solicitudes','solicitudes.tratamiento_id = tratamientos.id','left')
                ->join('estados','estados.id = solicitudes.estado_id','left')
                ->join('prescripciones','tratamientos.id = prescripciones.id_tratamiento','left')
                ->where($arrayWhere)
                ->groupBy('tratamientos.id') //Para evitar duplicados por las prescripciones
                ->orderBy('cip', 'asc');
        
        //Se añaden las sentencias like al where
        $strLike = '';
        foreach($arrayLike as $likeField=>$likeValue){
          $strLike .= " OR (" . $likeField . " LIKE '%".$likeValue."%') ";
        }
        if ($strLike != '') $tratamientos->where("(".substr($strLike, 4).")");

        //Se añaden las sentencias where de los campos de fecha que vengan en el parámetro arrayDates
        foreach($arrayDates as $dateField){
          $strWhere = "(".$dateField." IS NULL OR ".$dateField.">='".Carbon::now()->toDateString()."')";
          $tratamientos->where($strWhere);
        }

        //Se añaden las sentencias de filtros de fechas por intervalos
        foreach($arrayBetweenDates as $betweenField=>$betweenValue){
          $tratamientos->where($betweenField ."'". $betweenValue ."'");
        }
         
        //Se añade filtro de servicios
        if($arrayServeis != null && count($arrayServeis) > 0){
          $tratamientos->whereIn('tratamientos.servicio_codigo', $arrayServeis) ;
        }

        return $tratamientos->findAll();
        
      }

      /**
       * Retorna el último tratamiento (última fecha de ingreso) del paciente (cip) indicado
       * @return Tratamiento
       */
      public function findTratamientoCompleto($idTratamiento)
      {
        return $this
            ->select(["`tratamientos`.*",  "solicitudes.fecha_cambio_confirmado",
            "CONCAT(tratamientos.nombre_paciente, ' ') AS nombre_completo",
            "IF(confirmado_hasta IS NULL OR confirmado_hasta >='".Carbon::now()->toDateString()."',0,1) AS caducat",
            "IFNULL(estados.id, 1) as estado_id",
            "IFNULL(estados.descripcion,'" . $this->estadoInicial . "') as estado", 
            "IFNULL(estados.clase_bootstrap,'" . $this->boostrapEstadoInicial . "') as bootstrap_class",
            "GROUP_CONCAT(DISTINCT CONCAT(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.codigo_indicacion, null), ': ', IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.indicacion, null)) SEPARATOR '; ') AS prescripciones_indicacion",
            "GROUP_CONCAT(DISTINCT CONCAT(prescripciones.codigo_indicacion, ': ', prescripciones.indicacion) SEPARATOR '; ') AS prescripciones_indicacion_totales",
            "COUNT(prescripciones.id_prescripcion) AS prescripciones_totales",
            "SUM(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."',0,1)) AS prescripciones_caducadas",
            "GROUP_CONCAT(DISTINCT IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.usuario_prescripcion, null) SEPARATOR '; ') AS prescripciones_prescriptores",
            "GROUP_CONCAT(DISTINCT prescripciones.usuario_prescripcion SEPARATOR '; ') AS prescripciones_prescriptores_totals"],FALSE)
            ->join('solicitudes','solicitudes.tratamiento_id = tratamientos.id','left')
            ->join('estados','estados.id = solicitudes.estado_id','left')
            ->join('prescripciones','tratamientos.id = prescripciones.id_tratamiento','left')
            ->where('tratamientos.id = ' . $idTratamiento)
            ->groupBy('tratamientos.id') 
            ->first();
      }


      /**
       * Lista los tratamientos que tienen confirmado_hasta hasta la fecha indicada
       * @return array
       */
      public function findConfirmadosHasta($fechaConfirmadoHasta, $diaExacto = false)
      {
        if($diaExacto){
        $fechaConfirmadoHasta = Carbon::parse($fechaConfirmadoHasta)->toDateString();
        $strWhere = "DATE(confirmado_hasta) = '". $fechaConfirmadoHasta . "'";
        }else{
        $strWhere = "confirmado_hasta >= NOW() AND confirmado_hasta <='". $fechaConfirmadoHasta . "'";
        }
        $tratamientos = $this
                ->select(["`tratamientos`.*",  "solicitudes.fecha_cambio_confirmado",
                "CONCAT(tratamientos.nombre_paciente, ' ') AS nombre_completo",
                "IF(confirmado_hasta IS NULL OR confirmado_hasta >='".Carbon::now()->toDateString()."',0,1) AS caducat",
                "IFNULL(estados.id, 1) as estado_id",
                "IFNULL(estados.descripcion,'" . $this->estadoInicial . "') as estado", 
                "IFNULL(estados.clase_bootstrap,'" . $this->boostrapEstadoInicial . "') as bootstrap_class",
                "GROUP_CONCAT(DISTINCT CONCAT(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.codigo_indicacion, null), ': ', IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.indicacion, null)) SEPARATOR '; ') AS prescripciones_indicacion",
                "COUNT(prescripciones.id_prescripcion) AS prescripciones_totales",
                "SUM(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."',0,1)) AS prescripciones_caducadas",
                "GROUP_CONCAT(DISTINCT IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.usuario_prescripcion, null) SEPARATOR '; ') AS prescripciones_prescriptores"],FALSE)
                ->join('solicitudes','solicitudes.tratamiento_id = tratamientos.id','left')
                ->join('estados','estados.id = solicitudes.estado_id','left')
                ->join('prescripciones','tratamientos.id = prescripciones.id_tratamiento','left')
                ->where($strWhere)
                ->groupBy('tratamientos.id') 
                ->orderBy('confirmado_hasta', 'asc');
        
      
        return $tratamientos->findAll();

      }

       /**
       * Lista los tratamientos que tienen fecha de alta (y como máximo hasta la fecha indicada)
       * @return array
       */
      public function findAltesFins($dataMax = null )
      {
        if($dataMax == null){
        $dataMax = Carbon::now()->toDateString();
        }
        
        $strWhere = "fecha_alta IS NOT NULL AND fecha_alta <='". $dataMax . "'";
        
        $tratamientos = $this
                ->select(["`tratamientos`.*",  "solicitudes.fecha_cambio_confirmado",
                "CONCAT(tratamientos.nombre_paciente, ' ') AS nombre_completo",
                "IF(confirmado_hasta IS NULL OR confirmado_hasta >='".Carbon::now()->toDateString()."',0,1) AS caducat",
                "IFNULL(estados.id, 1) as estado_id",
                "IFNULL(estados.descripcion,'" . $this->estadoInicial . "') as estado", 
                "IFNULL(estados.clase_bootstrap,'" . $this->boostrapEstadoInicial . "') as bootstrap_class",
                "GROUP_CONCAT(DISTINCT CONCAT(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.codigo_indicacion, null), ': ', IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.indicacion, null)) SEPARATOR '; ') AS prescripciones_indicacion",
                "COUNT(prescripciones.id_prescripcion) AS prescripciones_totales",
                "SUM(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."',0,1)) AS prescripciones_caducadas",
                "GROUP_CONCAT(DISTINCT IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.usuario_prescripcion, null) SEPARATOR '; ') AS prescripciones_prescriptores"],FALSE)
                ->join('solicitudes','solicitudes.tratamiento_id = tratamientos.id','left')
                ->join('estados','estados.id = solicitudes.estado_id','left')
                ->join('prescripciones','tratamientos.id = prescripciones.id_tratamiento','left')
                ->where($strWhere)
                ->groupBy('tratamientos.id') 
                ->orderBy('fecha_alta', 'asc');
        
        return $tratamientos->findAll();
      }
      
       /**
       * Lista los tratamientos que tienen fecha de exitus (y como máximo hasta la fecha indicada)
       * @return array
       */
      public function findExitusHasta($dataMax = null )
      {
        if($dataMax == null){
        $dataMax = Carbon::now()->toDateString();
        }
        
        $strWhere = "fecha_exitus IS NOT NULL AND fecha_exitus <='". $dataMax . "'";
        
        $tratamientos = $this
                ->select(["`tratamientos`.*",  "solicitudes.fecha_cambio_confirmado",
                "CONCAT(tratamientos.nombre_paciente, ' ') AS nombre_completo",
                "IF(confirmado_hasta IS NULL OR confirmado_hasta >='".Carbon::now()->toDateString()."',0,1) AS caducat",
                "IFNULL(estados.id, 1) as estado_id",
                "IFNULL(estados.descripcion,'" . $this->estadoInicial . "') as estado", 
                "IFNULL(estados.clase_bootstrap,'" . $this->boostrapEstadoInicial . "') as bootstrap_class",
                "GROUP_CONCAT(DISTINCT CONCAT(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.codigo_indicacion, null), ': ', IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.indicacion, null)) SEPARATOR '; ') AS prescripciones_indicacion",
                "COUNT(prescripciones.id_prescripcion) AS prescripciones_totales",
                "SUM(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."',0,1)) AS prescripciones_caducadas",
                "GROUP_CONCAT(DISTINCT IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.usuario_prescripcion, null) SEPARATOR '; ') AS prescripciones_prescriptores"],FALSE)
                ->join('solicitudes','solicitudes.tratamiento_id = tratamientos.id','left')
                ->join('estados','estados.id = solicitudes.estado_id','left')
                ->join('prescripciones','tratamientos.id = prescripciones.id_tratamiento','left')
                ->where($strWhere)
                ->groupBy('tratamientos.id') 
                ->orderBy('fecha_exitus', 'asc');
        
        return $tratamientos->findAll();
      }

       /**
       * Lista los tratamientos que tienen estado pendiente o no tienen estado
       * @return array
       */
      public function findPendientes()
      {
       
        $tratamientos = $this
                ->select(["`tratamientos`.*",  "solicitudes.fecha_cambio_confirmado",
                "CONCAT(tratamientos.nombre_paciente, ' ') AS nombre_completo",
                "IF(confirmado_hasta IS NULL OR confirmado_hasta >='".Carbon::now()->toDateString()."',0,1) AS caducat",
                "IFNULL(estados.id, 1) as estado_id",
                "IFNULL(estados.descripcion,'" . $this->estadoInicial . "') as estado", 
                "IFNULL(estados.clase_bootstrap,'" . $this->boostrapEstadoInicial . "') as bootstrap_class",
                "GROUP_CONCAT(DISTINCT CONCAT(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.codigo_indicacion, null), ': ', IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.indicacion, null)) SEPARATOR '; ') AS prescripciones_indicacion",
                "COUNT(prescripciones.id_prescripcion) AS prescripciones_totales",
                "SUM(IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."',0,1)) AS prescripciones_caducadas",
                "GROUP_CONCAT(DISTINCT IF(prescripciones.fecha_fin IS NULL OR prescripciones.fecha_fin >'".Carbon::now()->toDateString()."', prescripciones.usuario_prescripcion, null) SEPARATOR '; ') AS prescripciones_prescriptores"],FALSE)
                ->join('solicitudes','solicitudes.tratamiento_id = tratamientos.id','left')
                ->join('estados','estados.id = solicitudes.estado_id','left')
                ->join('prescripciones','tratamientos.id = prescripciones.id_tratamiento','left')
                ->where( 'estados.id', 1) //estado 1 = Pendiente
                ->orWhere('estados.id IS NULL') //Si no tiene estado, también es pendiente
                ->groupBy('tratamientos.id') 
                ->orderBy('confirmado_hasta', 'asc');
        
        return $tratamientos->findAll();

      }


      public function getServicios()
      {
        return $this->distinct()->select("servicio_codigo, servicio")->orderBy('servicio','asc')->findAll();
      }


        /**
        * Retorna el total de tratamientos, pendientes y aprobados
        * @return array
        */
      public function getTotalesByEstado()
      {
       
        $totals = $this->select(["COUNT(tratamientos.id) as totales, 
                SUM(IF(estados.id = 1 OR estados.id IS NULL,1,0)) as pendientes,
                SUM(IF(estados.id = 3,1,0)) as aprobadas"
                ],FALSE)
                ->join('solicitudes','solicitudes.tratamiento_id = tratamientos.id','left')
                ->join('estados','estados.id = solicitudes.estado_id','left');                     
        return $totals->first();
      }

    }
