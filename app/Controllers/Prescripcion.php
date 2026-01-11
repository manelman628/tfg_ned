<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Prescripcion as PrescripcionModel;


class Prescripcion extends BaseController
{

  public function getPrescripcionesTratamiento()
  {
     
    $input = $this->request->getPost();

    $prescripcionModel = new PrescripcionModel();
    $prescripciones = $prescripcionModel->getPrescripcionesTratamiento($input['tratamiento_id'], $input['muestra_caducadas'] === 'true');
     
    return $this->response->setJSON(['prescripciones' => $prescripciones]);
    
  }
   
}

