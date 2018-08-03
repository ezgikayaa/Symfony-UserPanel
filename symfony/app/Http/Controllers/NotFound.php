<?php

use TowerUIX\Http\Controller;

class NotFound extends Controller {

    public function index(){
        $this->View('404');
    }
}
