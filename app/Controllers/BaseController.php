<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $gestorAcces = service('auth');
        if($gestorAcces->isLogged(false)){
            //gestión de roles
            $this->permissions['listar tratamientos'] = $gestorAcces->can('listar tratamientos', env('app.appName')) ? true : false;
            $this->permissions['modificar tratamiento'] = $gestorAcces->can('modificar tratamiento', env('app.appName')) ? true : false;
            $this->permissions['eliminar tratamiento'] = $gestorAcces->can('eliminar tratamiento', env('app.appName')) ? true : false;
            $this->permissions['modificar prescripcion'] = $gestorAcces->can('modificar prescripcion', env('app.appName')) ? true : false;

            $this->permissions['modificar paciente'] = $gestorAcces->can('modificar paciente', env('app.appName')) ? true : false;

            $this->permissions['ver avisos'] = $gestorAcces->can('ver avisos', env('app.appName')) ? true : false;
            $this->permissions['modificar aviso'] = $gestorAcces->can('modificar aviso', env('app.appName')) ? true : false;


        }
    }
}
