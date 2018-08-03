<?php

namespace TowerUIX\Kernel;

use TowerUIX\Kernel\Contracts\BundleInterface;
use TowerUIX\Src\Helpers;
use Twig_Loader_Filesystem;
use Twig_Environment;


class Bundle implements BundleInterface {

    private $__Loader;
    public $__Twig;

    public function __construct() {
      
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        define('dil_id', 1);

        $this->__Loader = new Twig_Loader_Filesystem("views");
        $this->__Twig = new Twig_Environment($this->__Loader, []);

       
     

        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $this->__Twig->addGlobal('host', "https://{$_SERVER['HTTP_HOST']}/");
            define('url', "http://localhost/symfony/");
        } else {
            $this->__Twig->addGlobal('host', "http://{$_SERVER['HTTP_HOST']}/");
            define('url', "http://localhost/symfony/");
        }

$modul= \m_Modül::all(array('order'=>'sira asc'));
$modulgoruntule= \m_Modül_İzinleri::all();
  
      foreach ($modulgoruntule as $value){
            
              if($value->grup_id==$_SESSION['grupid']){
                 
                  $ModulGoruntule[$value->modul_id][]=[  
                      'Goruntule'=> $value->goruntule,                   
                  ];             
         } 
         }
        

      $_SESSION['usergoruntuleyetki']= $ModulGoruntule[1][0]['Goruntule'];
      $_SESSION['grupgoruntuleyetki']= $ModulGoruntule[2][0]['Goruntule'];
      $_SESSION['loggoruntuleyetki']= $ModulGoruntule[3][0]['Goruntule'];

        $this->__Twig->addGlobal('url', url);
        $this->__Twig->addGlobal('modul', $modul);
        $this->__Twig->addGlobal('modulgoruntule', $ModulGoruntule);

    }

    


}
