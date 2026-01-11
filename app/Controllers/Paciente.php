<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Paciente as PacienteModel;
use App\Entities\Paciente as PacienteEntity;

class Paciente extends BaseController
{

   
    public function getPaciente()
    {
        $input = $this->request->getPost();

        $paciente = new PacienteEntity();
        $pacienteModel = new PacienteModel();
        if (isset($input['cip'])){
            $paciente = $pacienteModel->findPacienteByCip($input['cip']);
        }elseif (isset($input['nhc'])){
            $paciente = $pacienteModel->findPacienteByNHC($input['nhc']);
        }
        
        return $this->response->setJSON($paciente);
    }

  
    public function saveDireccion()
    {
        $resultat = ['error' => 0, 'txterror' => ''];
    
        $input = $this->request->getPost();
    
        if(isset($input['cip'])){
          
          $pacienteModel = new pacienteModel();
          
          $paciente = $pacienteModel->findPacienteByCip($input['cip']);
        
          if(!$paciente){
            $paciente = new PacienteEntity();
            $paciente->setUsuarioCreacion();
            $paciente->cip_paciente = $input['cip'];
            $paciente->nhc = $input['nhc'];
          }
          
          $paciente->calle = $input['calle'];
          $paciente->poblacion = $input['poblacion'];
          $paciente->codigo_postal = $input['codigo_postal'];
          $paciente->setUsuarioModificacion();
          try{   
            if($pacienteModel->save($paciente)){
              $resultat['error'] = 0;
              $resultat['txterror'] = '';
            }else{
              error_log(print_r($pacienteModel->errors(),true));
              $resultat['error'] = 1;
              $resultat['txterror'] = 'No s\'han pogut guardar les dades del paciente';
            }
          }catch(Exception $ex){
            if ($ex->getMessage() != "There is no data to update."){
              $resultat['error'] = 1;
              $resultat['txterror'] = 'No hi ha canvis a guardar';
            }
          }

        }else{
          $resultat['error'] = 1;
          $resultat['txterror'] = 'No s\'ha pogut guardar. No es troba CIP';
        }
    
        return $this->response->setJSON($resultat);
      }

      public function saveTelefono()
    {
        $resultat = ['error' => 0, 'txterror' => ''];
    
        $input = $this->request->getPost();
    
        if(isset($input['cip'])){
          
          $pacienteModel = new pacienteModel();
          
          $paciente = $pacienteModel->findPacienteByCip($input['cip']);
        
          if(!$paciente){
            $paciente = new PacienteEntity();
            $paciente->cip_paciente = $input['cip'];
            $paciente->nhc = $input['nhc'];
            $paciente->setUsuarioCreacion();
          }
         
          $paciente->telefono = $input['telefono'];
          $paciente->setUsuarioModificacion();
          try{
            if($pacienteModel->save($paciente)){
              $resultat['error'] = 0;
              $resultat['txterror'] = '';
            }else{
              $resultat['error'] = 1;
              $resultat['txterror'] = 'No s\'han pogut guardar les dades del paciente';
            }
          }catch(Exception $ex){
            if ($ex->getMessage() != "There is no data to update."){
              $resultat['error'] = 0;
              $resultat['txterror'] = 'No s\'han pogut guardar les dades del paciente';
            }
          }
        }else{
          $resultat['error'] = 1;
          $resultat['txterror'] = 'No s\'ha pogut guardar. No es troba CIP';
        }
    
        return $this->response->setJSON($resultat);
      }


      public function deleteDireccion()
      {
          $resultat = ['error' => 0, 'txterror' => ''];
      
          $input = $this->request->getPost();
      
          if(isset($input['cip'])){
            
            $pacienteModel = new pacienteModel();
            $paciente = $pacienteModel->findPacienteByCip($input['cip']);
          
            if($paciente){
                $paciente->unsetDireccion();
                $paciente->setUsuarioModificacion();
                                          
                if($pacienteModel->save($paciente)){
                    $resultat['error'] = 0;
                    $resultat['txterror'] = '';
                }else{
                    $resultat['error'] = 1;
                    $resultat['txterror'] = 'No s\'han pogut guardar les dades del paciente';
                }
            }
      
          }else{
            $resultat['error'] = 1;
            $resultat['txterror'] = 'No s\'ha pogut guardar. No es troba CIP';
          }
      
          return $this->response->setJSON($resultat);
        }

        public function deleteTelefono()
      {
          $resultat = ['error' => 0, 'txterror' => ''];
      
          $input = $this->request->getPost();
      
          if(isset($input['cip'])){
            
            $pacienteModel = new pacienteModel();
            $paciente = $pacienteModel->findPacienteByCip($input['cip']);
          
            if($paciente){
                $paciente->unsetTelefono();
                $paciente->setUsuarioModificacion();
                                          
                if($pacienteModel->save($paciente)){
                    $resultat['error'] = 0;
                    $resultat['txterror'] = '';
                }else{
                    $resultat['error'] = 1;
                    $resultat['txterror'] = 'No s\'han pogut guardar les dades del paciente';
                }
            }
      
          }else{
            $resultat['error'] = 1;
            $resultat['txterror'] = 'No s\'ha pogut guardar. No es troba CIP';
          }
      
          return $this->response->setJSON($resultat);
        }
    

}

