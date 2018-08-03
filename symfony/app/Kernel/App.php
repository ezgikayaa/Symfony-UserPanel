<?php

/***
 * TowerUIX Kernel App
 * @package toweruix/kernel/app
*/

namespace TowerUIX\Kernel;

use TowerUIX\Kernel\Contracts\AppInterface;
use ActiveRecord\Config;

class App extends Bundle implements AppInterface
{

    public static $ConfigurationFiles;

    /**
     * Config Loader Functions
     * @param array
     * @return mixed
    */
    public static function ConfigLoader(array $LoadConfigs)
    {
        foreach($LoadConfigs as $ConfigFile){
            self::$ConfigurationFiles = require_once "config/{$ConfigFile}.php";
        }
    }

    

    /**
     * View Function
     * @param string
     * @return string
    */
    public function View($Parameter = "default", $Data = [])
    {
        // Replace @ to /
        $ReplaceFile = str_replace("@","/",$Parameter);
        $TwigFile    = $ReplaceFile.".twig";
        // Render twig file
        echo $this->__Twig->render($TwigFile, $Data);
    }




}