<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Ayuda extends BaseController
{
  public function encuantoa(){
    $session = session();
    $data['pagina'] = 'encuantoa';
    $data['breadcrumb'] = [["Principal", base_url()],["Ayuda",""],["En cuanto a",""]];
    $gestorAcces = service('auth');
    if($gestorAcces->isLogged(false)){
      $data['usuario'] = $gestorAcces->getNomiCognoms();
      $data['permissions'] = $this->permissions;
    }
    return view('ayuda/encuantoa', $data);
  }

  public function notalegal(){
    $session = session();
    $data['pagina'] = 'notalegal';
    $data['breadcrumb'] = [["Principal", base_url()],["Ayuda",""],["Nota legal",""]];
    $gestorAcces = service('auth');
    if($gestorAcces->isLogged(false)){
     $data['usuario'] = $gestorAcces->getNomiCognoms();
     $data['permissions'] = $this->permissions;
    }
    return view('ayuda/notalegal', $data);
  }
}
