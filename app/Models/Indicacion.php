<?php

namespace App\Models;

use CodeIgniter\Model;
use Carbon\Carbon;

class Indicacion extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'indicaciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'App\Entities\Indicacion';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
      'codigo',
      'descripcion',
      'grupo',
      'subgrupo',
      'via'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_modificacion';
    protected $deletedField  = 'fecha_eliminacion';

    // Validation
    protected $validationRules = [    ];
    protected $validationMessages   = [    ];

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
     * Retorna la indicació con el código indicado
     * @return Indicacion
     */
    public function findIndicacionByCodigo($codi)
    {
      return $this->select('*')->where('codi', $codi)->first();
    }

    /**
     * Obtiene un array con todas las indicacioneses de las prescripciones de un tratamiento
     * @return array Indicaciones
     */
    public function findIndicacionesTratamiento($tratamiento_id, $compruebaCaducadas = false): array
    {
      $indicaciones = $this->select('indicaciones.*, prescripciones.id_prescripcion, prescripciones.fecha_inicio, prescripciones.fecha_fin')
                  ->join('prescripciones','prescripciones.codigo_indicacion = indicaciones.codigo') 
                  ->where('prescripciones.id_tratamiento', $tratamiento_id)
                  ->orderBy('prescripciones.id_prescripcion', 'ASC')
                  ->groupBy('indicaciones.codigo');

        if (!$compruebaCaducadas) {
          $indicaciones = $this->where('(fecha_fin IS NULL OR fecha_fin >\''.Carbon::now()->toDateString().'\')');
        }
      return $indicaciones->findAll();
    }
    

}