<?php

namespace App\Models;

use CodeIgniter\Model;
use Carbon\Carbon;

class Prescripcion extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'prescripciones';
    protected $primaryKey       = 'id_prescripcion';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'App\Entities\Prescripcion';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
      'id_tratamiento',
      'id_prescripcion_inicial',
      'indicacion',
      'codigo_indicacion',
      'fecha_inicio',
      'fecha_fin',
      'valida_hasta',
      'fecha_hasta_toma',
      'medi_desc',
      'consejos_admin',
      'cip_prescripcion',
      'via_fk',
      'via_codigo',
      'via_descripcion',
      'frecuencia_fk',
      'frec_codigo',
      'frec_descripcion',
      'frec_tipo',
      'dosis',
      'usuario_prescripcion',
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
     * Retorna las prescripciones associades al tratamiento indicat ordenades per data
     * @param int $tratamiento
     * @return Tratamiento
     */
    public function getPrescripcionesTratamiento($tratamiento, $muestraCaducadas = false): array
    {
      $prescripciones = $this->select('prescripciones.*, IFNULL(fecha_fin,\'9999-12-31\') AS fecha_fin_efectiva,
                        IF(fecha_fin IS NULL OR fecha_fin >\''.Carbon::now()->toDateString().'\',0,1) AS caducada')
                      ->where('id_tratamiento', $tratamiento)
                      ->orderBy('fecha_fin_efectiva', 'desc')
                      ->orderBy('fecha_inicio', 'desc');
                      
      if (!$muestraCaducadas) {
        $prescripciones = $this->where('(fecha_fin IS NULL OR fecha_fin >\''.Carbon::now()->toDateString().'\')');
      }
      return $prescripciones->findAll();
    }


}
