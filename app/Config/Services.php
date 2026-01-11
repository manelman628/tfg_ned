<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use ICSEbre\Auth\Config;
use ICSEbre\Auth\GestorAcces;
use ICSEbre\Auth\Enums\AuthConnector;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

        public static function auth($getShared = true): GestorAcces
        {
            if($getShared)
            {
                return static::getSharedInstance('auth');
            }
            $config = new Config(
            cas_ip: env('cas.ip'),
            cas_port: env('cas.port'),
            ldap_user: env('ldap.user'),
            ldap_domain: env('ldap.domain'),
            ldap_pass: env('ldap.pass'),
            ldap_ip: env('ldap.ip'),
            ldap_port: env('ldap.port'),
            aplicacio: env('app.appName'),
            permissions: PERMISSIONS,
            nom_aplicacio: env('app.titleName'),
            ruta_autenticacio: url_to('authenticate'),
            tipus_connector: AuthConnector::ActiveDirectory
            );
            return new GestorAcces(config:$config, session:\Config\Services::session());
        }
}
