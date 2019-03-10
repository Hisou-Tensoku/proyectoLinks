<?php

namespace izv\mvc;

class Router {
    
    //Establece las rutas del modelo, la vista y el controlador dependiendo de la ruta

    private $rutas, $ruta;
    
    function __construct($ruta) {
        $this->rutas = array(
            'ajax' => new Route('AjaxModel', 'AjaxView', 'AjaxController'),
            'index' => new Route('DefaultModel', 'DefaultView', 'DefaultController'),
            'user' => new Route('UserModel', 'UserView', 'UserController')
        );
        $this->ruta = $ruta;
    }
    
    //Devuelve una instancia de la clase route
    function getRoute() {
        $ruta = $this->rutas['index'];
        if(isset($this->rutas[$this->ruta])) {
            $ruta = $this->rutas[$this->ruta];
        }
        return $ruta;
    }
}