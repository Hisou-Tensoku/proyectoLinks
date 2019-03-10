<?php

namespace izv\controller;

use izv\app\App;
use izv\model\Model;
use izv\tools\Session;
use izv\tools\Util;
use izv\tools\Reader;
use izv\data\Usuario;
use izv\tools\Mail;

class DefaultController extends Controller {

    function __construct(Model $model) {
        parent::__construct($model);
        //...
    }

    function dologin() {
        if($this->getSession()->isLogged()) {
            header('Location: ' . App::BASE . 'index?op=login&r=session');
            exit();
        }
        
        $user = Reader::read('correo');
        $clave= Reader::read('clave');
        
        $login = $this->getModel()->login($user, $clave);
        
        if($login) {
            $this->getSession()->login($login);
        } else {
            header('Location: index?op=login&r=0');
            exit;
        }
        
        
        header('Location: ../user?op=login&r=1');
        exit;
    }

    function dologout() {
        $this->getSession()->logout();
        header('Location: ' . App::BASE . 'index');
        exit();
    }

    function doregister() {
        if ($this->getSession()->isLogged()) {
           header('Location: ../index');
            exit; 
        }
        
        $usuario = Reader::readObject('izv\data\Usuario');
        echo Util::varDump($usuario);
        
        $clave2 = Reader::read('clave2');
        
        //3º validación de datos
        if($usuario->getClave() !== $clave2 ||
            mb_strlen($usuario->getClave()) < 3) {
            //5º producir resultado -> redirección
            header('Location: ' . App::BASE . 'index?op=register&r=password');
            exit();
        }
        if (!filter_var($usuario->getCorreo(), FILTER_VALIDATE_EMAIL)) {
            //5º producir resultado -> redirección
            header('Location: ' . App::BASE . 'index?op=register&r=email');
            exit();
        }

        //4º usar el modelo
        $usuario->setClave(Util::encriptar($usuario->getClave()));
        $usuario->setActivo(false);
        $usuario->setAdministrador(false);
        
        $r = $this->getModel()->register($usuario);
        
        if ($r>0) {
            if($usuario->getId() != null) {
                Mail::sendActivation($usuario);
            } else {
                header('Location: index?op=register&r=mail');
                exit;
            }
        }

        //5º producir resultado -> redirección
        header('Location: ' . App::BASE . 'index?op=register&r=' . $r);
        exit(); 
    }
    
    function activar() {
        if ($this->getSession()->isLogged()) {
           header('Location: ../user/dologout');
            exit; 
        }
        $id = Reader::read('id');
        $code = Reader::read('code');
        
        $resultado = $this->getModel()->activar($code, $id);
        echo Util::varDump($resultado);
        $url = 'index?op=activate&r=' . $resultado;
        header('Location: ' . $url);
        exit();
    }

    function login() {
        if(!$this->getSession()->isLogged()) {
            $this->getModel()->set('twigFile', '_login.html');
        }
    }

    function main() {
        //1º control de sesión
        if($this->getSession()->isLogged()) {
            $this->getModel()->set('twigFile', '_mainlogged.html');
            $this->getModel()->set('user', $this->getSession()->getLogin()->getCorreo());
            if($this->getSession()->isRoot()) {
                $this->getModel()->set('administrador', true);
            }
        } else {
            //5º producir resultado
            $this->getModel()->set('twigFile', '_main.html');
        }
    }

    function otra() {
        $this->getModel()->set('twigFile', '_otra.html');
    }

    function register() {
        if(!$this->getSession()->isLogged()) {
            $this->getModel()->set('twigFile', '_register.html');
        }
    }
}