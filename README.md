# Gestión de Nutrición Enteral Domiciliaria

Este repositorio contiene el código de la aplicación Gestión de la Nutrición Enterla Domiciliaria bajo la estructura del framework CodeIgniter4, del TFG elaborado por Manuel Segura.
También incluye los scripts necesarios para construir la Base de Datos en el directorio SQL.
Se incluyen también los Jobs y Transformations de Pentaho Kettle utilizados en la ETL.
Se incluye el PDF del manual de usuario de la aplicación.


## Installation & updates

`composer intall` then `composer update` whenever there is a new release of the framework.

`npm install`, `npm init` and `npm run build` for javascript including packages

## Setup

Configure `.env` and tailor for your app, specifically the baseURL and any database settings.


## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
