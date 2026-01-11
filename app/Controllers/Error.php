<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Error extends BaseController
{
    public function __construct(){

	}
 
    public function not_logged_in(){
        $data['pagina'] = 'error';
        $data['breadcrumb'] = [["Principal", base_url()],[lang('Messages.error_no_login'), '']];
        $gestorAcces = service('auth');
        if($gestorAcces->isLogged(false)){
        $data['usuario'] = $gestorAcces->getNomiCognoms();
        }
        return view('errors/html/error_not_logged_in', $data);
    }
 
    public function no_role_in_app(){
        $data['pagina'] = 'error';
        $data['breadcrumb'] = [["Principal", base_url()],[lang('Messages.error_no_role'), '']];
        $gestorAcces = service('auth');
            if($gestorAcces->isLogged(false)){
                $data['usuario'] = $gestorAcces->getNomiCognoms();
            }
        return view('errors/html/error_no_role', $data);

    }
 
	public function no_permission(){
		$session = session();
		$pagina = $session->getFlashdata('pagina') ? $session->getFlashdata('pagina') : 'error';
		$data['pagina'] = $pagina;
        $data['breadcrumb'] = [["Principal", base_url()],[lang('Messages.error_no_permission'), '']];
        $gestorAcces = service('auth');
		if($gestorAcces->isLogged(false)){
			$data['usuario'] = $gestorAcces->getNomiCognoms();
		}
		return view('errors/html/error_no_permission', $data);
	}
}
