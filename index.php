<?php

require 'classes/autoload.php';
require 'classes/vendor/autoload.php';

$param = '';
if(isset($_GET['params'])) {
    $param = $_GET['params'];
}
$partes = explode('/', $param);
$ruta = '';
$accion = '';
if(isset($partes[0])) {
    $ruta = $partes[0];
}
if(isset($partes[1])) {
    $accion = $partes[1];
}

$frontController = new izv\mvc\FrontController($ruta, $accion);
$frontController->doAction();
echo $frontController->render();