<?php

namespace izv\view;

use izv\model\Model;
use izv\tools\Util;

class DefaultView extends View {

    function __construct(Model $model) {
        parent::__construct($model);
        $this->getModel()->set('twigFolder', 'twigtemplates/maundy/');
        $this->getModel()->set('twigFile', '_main.html');
    }

    function render($accion) {
        
        require 'classes/vendor/autoload.php';
        
        $data = $this->getModel()->getViewData();
        $loader = new \Twig_Loader_Filesystem($data['twigFolder']);
        $twig = new \Twig_Environment($loader);
        return $twig->render($data['twigFile'], $data);
    }
}