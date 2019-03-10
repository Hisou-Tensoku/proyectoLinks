<?php

namespace izv\mvc;

class FrontController {

    private $model, $view, $controller;
    private $accion;

    function __construct($ruta, $accion) {
        $router = new Router($ruta);
        $route = $router->getRoute();
        $this->accion = $accion;
        
        //Instancia el modelo, el controlador y la vista
        $model = $route->getModel();
        $this->model = new $model();
        $controller = $route->getController();
        $this->controller = new $controller($this->model);
        $view = $route->getView();
        $this->view = new $view($this->model);
    }
    
    //Si la acción existe en el controlador, se ejecuta la accion
    function doAction() {
        $accion = 'main';
        if(method_exists($this->controller, $this->accion)) {
            $accion = $this->accion;
        }
        $this->controller->$accion();
    }
    
    //La vista renderiza la 
    function render() {
        return $this->view->render($this->accion);
    }
}