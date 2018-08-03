<?php


/*
|--------------------------------------------------------------------------
| Composer Otomatik Yükleyici
|--------------------------------------------------------------------------
|
| Composer paketlerini sisteme dahil eder.
|
*/

require_once __DIR__."/../vendor/autoload.php";

use TowerUIX\Kernel\App;
use ActiveRecord\Config;

/*
|--------------------------------------------------------------------------
| Config Loader
|--------------------------------------------------------------------------
|
| Boilerplate config dosyalarını sisteme dahil eder
|
*/

App::ConfigLoader([
    "database"
]);

Config::initialize(function($DatabaseConnection){

    $DatabaseConnection->set_model_directory(__DIR__."/Kernel/Models");
    $DatabaseConnection->set_connections(array(
        "development" => App::$ConfigurationFiles['driver']."://".App::$ConfigurationFiles['username'].":".App::$ConfigurationFiles['password']."@".App::$ConfigurationFiles['host']."/".App::$ConfigurationFiles['schema']."?charset=".App::$ConfigurationFiles['charset']
    ));

});


