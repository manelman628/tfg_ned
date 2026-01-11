<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioSilicon extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'usuarios_silicon';
    protected $primaryKey       = 'usuario';
    protected $useAutoIncrement = false;
    protected $insertID         = 0;
    protected $returnType       = 'App\Entities\UsuarioSilicon';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
      'id',
      'usuario', 
      'usuario_login',
      'usuario_servicio',
      'usuario_num_colegiado',
      'usuario_activo'
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

    


}
