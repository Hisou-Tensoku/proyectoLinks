<?php

spl_autoload_register( function ($clase) {
  //echo $clase;
  //$archivo = dirname(__FILE__) . '/' . $clase . '.php';
  $archivo = dirname(__FILE__) . '/' . str_replace('\\', '/', $clase) . '.php';
  if (file_exists($archivo)) {
    require($archivo);
  }
});