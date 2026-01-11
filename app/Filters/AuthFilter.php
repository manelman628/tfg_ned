<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $gestorAcces = service('auth');
        if(!$gestorAcces->isLogged(false)){
          return redirect('error_logged');
        }
        $path = $request->getPath();
        $explodedPath = explode('/', $path);
        if(isset($explodedPath[2]) && is_numeric($explodedPath[2])){
          $numRutaView = $explodedPath[2];
        }else{
          $numRutaView = 0;
        }

        $routeExceptions = [
          ltrim(route_to('list_tratamientos'),'/'),
          trim(route_to('list_tratamientos_filtrado', $numRutaView),'/'),
          ltrim(route_to('tratamiento'),'/'),
          trim(route_to('view_solicitud', $numRutaView),'/'),
          ltrim(route_to('print_solicitud'),'/'),
          ltrim(route_to('seguimiento'),'/'),
          ltrim(route_to('seguimientos_tratamiento'),'/'),
          ltrim(route_to('tipo_seguimientos'),'/'),
          ltrim(route_to('save_seguimiento'),'/'),
          ltrim(route_to('paciente'),'/'),
          ltrim(route_to('delete_direccion_paciente'),'/'),
          ltrim(route_to('delete_telefono_paciente'),'/'),
          ltrim(route_to('save_direccion_paciente'),'/'),
          ltrim(route_to('save_telefono_paciente'),'/'),
          trim(route_to('procesa_aviso'),'/'),
          
        ];

        if(!$gestorAcces->hasAplicacio(env('app.appName')) && !in_array($request->getPath(),$routeExceptions)){
          return redirect('error_rol');
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
