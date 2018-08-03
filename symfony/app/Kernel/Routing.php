<?php


namespace TowerUIX\Kernel;


use TowerUIX\Kernel\Contracts\RoutingInterface;

class Routing implements RoutingInterface
{

    protected $controller = 'Home';

    protected $method     = 'index';

    protected $params     = [];

    protected $url        = "";


    /**
     *
     * Constructor
     *
     */
    public function __construct()
    {

        $this->Parse();
        $this->Controller();

    }


    /**
     *
     * URL Parsing
     *
    */
    public function Parse(){

        if(isset($_GET['url'])){
            $this->url = explode("/", filter_var(rtrim($_GET['url'],"/"), FILTER_SANITIZE_URL));
        }

    }


    /**
     *
     * Controller Function
     *
    */
    public function Controller(){
      $url = $this->url;
        if(isset($url[0])){
            if(file_exists(__DIR__.'/../Http/Controllers/'. ucfirst($url[0]) .'.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }else{
                $this->controller = "NotFound";
            }
        }
        require_once __DIR__.'/../Http/Controllers/'. $this->controller .'.php';
        $this->controller = new $this->controller;
        if(isset($url[1])){
            if(method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }


}