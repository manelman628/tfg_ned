<?php

namespace App\Models;

use CodeIgniter\Model;

class Paciente extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pacientes';
    protected $primaryKey       = 'cip_paciente';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'App\Entities\Paciente';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
      'cip_paciente',
      'nhc',
      'calle',
      'poblacion',
      'codigo_postal',
      'telefono',
      'usuario_creacion',
      'usuario_modificacion',
      'usuario_eliminacion'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_modificacion';
    protected $deletedField  = 'fecha_eliminacion';

    // Validation
    protected $validationRules = [
      'cip_paciente'            => 'required'
    ];
    protected $validationMessages   = [
      'cip_paciente' => [
        'required' => 'campo CIP es obligatorio.'
      ]
    ];

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
     * Retorna el paciente con el CIP indicado
     * @return Paciente
     */
    public function findPacienteByCip($cip)
    {
      return $this->select('*')->where('cip_paciente', $cip)->first();
    }

    /**
     * Retorna el paciente con el nhc indicado
     * @return Paciente
     */
    public function findPacienteByNhc($nhc)
    {
      return $this->select('*')->where('nhc', $nhc)->first();
    }


}
