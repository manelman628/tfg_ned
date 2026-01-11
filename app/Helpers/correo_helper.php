<?php

if (!function_exists('getCorreosAviso')) {
    /**
    * Obtiene una dirección de correo de la configuración según el rol.
    * Si no se encuentran correos genéricos en la configuración, recupera los correos personales según el rol en la aplicación
     *
     * @param string $rol
     * @return string
     */
    function getCorreosAviso($rol): array
    {
        $correosGenericos = json_decode(CORREOS_GENERICOS,true);
        if (isset($correusGenerics[$rol]) && $correosGenericos[$rol] != '') {
            return explode(',',$correosGenericos[$rol]);
        } else {
            $gestorAcces = service('auth');
            return $gestorAcces->getCorreusRol(env('app.appName'), env('app.appName').'_'.$rol);
        }
    }
}

if (!function_exists('enviaCorreo')) {
    /**
     * Envia un correo electrónico.
     *
     * @param array $contenido
     * @param string $destinatarios
     * @return void
     */
    function enviaCorreo(array $contenido, array $destinatarios): array{   
        try{
    
        if(!isset($destinatarios) || !is_array($destinatarios) || count($destinatarios) == 0){
            return array();
        }
    
        $subject = $contenido['titulo'];
        $body = $contenido['cuerpo'];
        $from = 'informatica.ebre.ics@gencat.cat';
        $to = implode(',', $destinatarios);

        $email = \Config\Services::email();
        $email->setFrom($from);
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($body);
            
        if ($email->send(false)){
            //$email->printDebugger();
            return $destinatarios;
        }else{
            $email->printDebugger();
            return array();
        }
    
        }catch (Exception $e) {
        return array();
        }
    
    }
}
