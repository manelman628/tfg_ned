<?php

namespace App\Models;

use CodeIgniter\Model;
use Carbon\Carbon;

class CambioPrescripcion extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'cambios_prescripciones';
    protected $primaryKey       = 'id_cambios';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'App\Entities\CambiosPrescripcion';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
      'id_tratamiento',
      'id_prescripcion',
      'id_prescripcion_inicial',
      'indicacion',
      'codigo_indicacion',
      'fecha_inicio',
      'fecha_fin',
      'valida_hasta',
      'fecha_hasta_toma',
      'medi_desc',
      'consejos_admin',
      'cip',
      'via_fk',
      'via_codigo',
      'via_descripcion',
      'frecuencia_fk',
      'frec_codigo',
      'frec_descripcion',
      'frec_tipo',
      'control',
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

  /**
    * OBTENER CAMBIOS EN DATOS DE PRESCRIPCIONES POR FECHA INDICADA
    * 
    * Retorna todos los registros de cambios de prescripción, si no pasamos fecha tomamos la actual
    * 
    * @return array $cambios_prescripciones
    */
    public function findByDataControl($dataControl = null)
    {
      if ($dataControl == null){
        $dataControl  = Carbon::now()->toDateString();
      }
        $canvis_tractaments = $this->select(["*"])->where("control like '".$dataControl."%'")->findAll();

        return $canvis_tractaments;
    }

    /* Borra los registros de cambios de prescripción por fecha indicada */
    public function deleteByDataControl($dataControl = null)
    {
      if ($dataControl == null){
        $dataControl  = Carbon::now()->toDateString();
      }
        $canvis_tractaments = $this->delete("control like '".$dataControl."%'");

        return $canvis_tractaments;
    }


}
