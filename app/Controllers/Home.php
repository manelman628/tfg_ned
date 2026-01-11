<?php

namespace App\Controllers;

use App\Models\Aviso as AvisoModel;
use App\Models\Tratamiento as TratamientoModel;

class Home extends BaseController
{
    public function index(){
        helper('form');
        $session = session();
        $data['pagina'] = '';
        $data['breadcrumb'] = [["Principal", base_url()]];
        $data['alerta'] = $session->getFlashdata('alert') ? $session->getFlashdata('alert') : null;
        $gestorAcces = service('auth');
        if($gestorAcces->isLogged(false)){
            $data['usuario'] = $gestorAcces->getNomiCognoms();
            $data['rols'] = $gestorAcces->getPerfils(env('app.appName'));
        }

        if($gestorAcces->can('ver avisos', env('app.appName'))){
            $avisModel = new AvisoModel;
            $avisos = $avisModel->findAllWithFilters(['procesado' => 0],$data['rols'] ? $data['rols'][0] : null);
            if ($avisos) $data['avisos'] = count($avisos);
        }
        $tractamentModel = new TratamientoModel();
        $totals = $tractamentModel->getTotalesByEstado();
        $data['pendientes'] = $totals->pendientes ?? 0;
        $data['aprovadas'] = $totals->aprovadas ?? 0;
        $data['totales'] = $totals->totales ?? 0;
       
        return view('principal', $data);
    }

    public function logout(){
        $gestorAcces = service('auth');
        if($gestorAcces->getUsuari()){
          $gestorAcces->logout();
          return redirect('home');
        }
    }

    public function login(){
        $gestorAcces = service('auth');
        $resultat = $gestorAcces->login();
        return redirect('home');
    }

    public function authenticate(){
        $data = $this->request->getPost();
        $gestorAcces = service('auth');
        $resultat = $gestorAcces->authenticate($data);
        return redirect('home');
    }
     
}
