<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index', ['as' => 'home']);
$routes->get('/logout', 'Home::logout', ['as' => 'logout']);
$routes->get('/login', 'Home::login', ['as' => 'login']);
$routes->get('/error/logged', 'Error::not_logged_in', ['as' => 'error_logged']);
$routes->get('/error/rol', 'Error::no_role_in_app', ['as' => 'error_rol']);
$routes->get('/error/permiso', 'Error::no_permission', ['as' => 'error_permisos']);
$routes->post('/authenticate', 'Home::authenticate', ['as' => 'authenticate']);
$routes->get('/ayuda/encuantoa', 'Ayuda::encuantoa');
$routes->get('/ayuda/nota', 'Ayuda::notalegal');

//tratamientos y prescripciones
$routes->get('/tratamientos', 'Tratamiento::list', ['filter' => 'auth', 'as' => 'list_tratamientos']);
$routes->get('/tratamientos/(:num)', 'Tratamiento::list/$1', ['filter' => 'auth', 'as' => 'list_tratamientos_filtrado']);
$routes->post('/tratamientos/tratamiento', 'Tratamiento::getTratamiento', ['filter' => 'auth', 'as' => 'tratamiento']);
$routes->get('/tratamientos/solicitud/(:num)', 'Tratamiento::solicitud/$1', ['filter' => 'auth', 'as' => 'view_solicitud']);
$routes->post('/tratamientos/imprimir', 'Tratamiento::print', ['filter' => 'auth', 'as' => 'print_solicitud']);
$routes->get('/tratamientos/exporta', 'Tratamiento::export', ['filter' => 'auth', 'as' => 'export_tratamientos']);
$routes->post('/prescripciones', 'Prescripcion::getPrescripcionesTratamiento', ['filter' => 'auth', 'as' => 'prescripciones_tratamiento']);

//seguimientos
$routes->post('/seguimientos/seguimiento', 'Seguimiento::getSeguimiento', ['filter' => 'auth', 'as' => 'seguimiento']);
$routes->post('/seguimientos/tipus', 'Seguimiento::getTiposSeguimiento', ['filter' => 'auth', 'as' => 'tipo_seguimientos']);
$routes->post('/seguimientos', 'Seguimiento::getSeguimientosTratamiento', ['filter' => 'auth', 'as' => 'seguimientos_tratamiento']);
$routes->post('/seguimientos/guarda', 'Seguimiento::saveSeguimiento', ['filter' => 'auth', 'as' => 'save_seguimiento']);

//Pacientes
$routes->post('/pacientes/paciente', 'Paciente::getPaciente', ['filter' => 'auth', 'as' => 'paciente']);
$routes->post('/pacientes/guarda/direccion', 'Paciente::saveDireccion', ['filter' => 'auth', 'as' => 'save_direccion_paciente']);
$routes->post('/pacientes/guarda/telefono', 'Paciente::saveTelefono', ['filter' => 'auth', 'as' => 'save_telefono_paciente']);
$routes->post('/pacientes/delete/direccion', 'Paciente::deleteDireccion', ['filter' => 'auth', 'as' => 'delete_direccion_paciente']);
$routes->post('/pacientes/delete/telefono', 'Paciente::deleteTelefono', ['filter' => 'auth', 'as' => 'delete_telefono_paciente']);

//Sincronización
$routes->get('/avisos', 'Aviso::list', ['filter' => 'auth', 'as' => 'list_avisos']);
$routes->post('/avisos/procesa', 'Aviso::procesa', ['filter' => 'auth', 'as' => 'procesa_aviso']);

//rutas cli para ejecución de tareas progrmaadas CRON. (cambiar a ruta get para pruebas locales des de navegador)
$routes->get('/avisos/sincroniza', 'Aviso::sincroniza'); 
$routes->get('/avisos/finalizan', 'Aviso::finalizan'); 
$routes->get('/avisos/caducados', 'Aviso::caducados'); 
$routes->get('/avisos/pendientes', 'Aviso::pendientes');
$routes->get('/avisos/altas', 'Aviso::altas');
$routes->get('/avisos/exitus', 'Aviso::exitus');