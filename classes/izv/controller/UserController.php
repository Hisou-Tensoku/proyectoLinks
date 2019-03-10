<?php

namespace izv\controller;

use izv\app\App;
use izv\data\Usuario;
use izv\model\Model;
use izv\tools\Reader;
use izv\tools\Session;
use izv\tools\Util;

class UserController extends Controller {

    function __construct(Model $model) {
        parent::__construct($model);
        //...
    }
    /*
    proceso general:
    1º control de sesión
    2º lectura de datos
    3º validación de datos
    4º usar el modelo
    5º producir resultado (para la vista)
    */

    function dologout() {
        $this->getSession()->logout();
        header('Location: ' . App::BASE . 'index');
        exit();
    }
    
    function usuarios(){
        if($this->getSession()->isLogged()) {
            
            $pagina = Reader::read('pagina');
            if($pagina === null || !is_numeric($pagina)) {
                $pagina =1;
            }
            
            $orden = Reader::read('orden');
            $ordenes = array(
                'id' => 'id',
                'correo' => 'correo',
                'alias' => 'alias',
                'nombre' => 'nombre',
                'fechaalta' => 'fecha'
            );
            if(!isset($ordenes[$orden])) {
                $orden='id';
            }
            $filter = Reader::read('filtro');
            
            $r = $this->getModel()->getUsers($pagina, $orden, $filter);
            $this->getModel()->add($r);
            
            $this->getModel()->set('placeholder', 'Usuario');
            $this->getModel()->set('admin', $this->getSession()->isRoot() ? true : false);
            //$this->getModel()->set('usuarios', $this->getModel()->getUsers());
            $this->getModel()->set('twigFile', '_usuarios.html');
        } else {
            if ($this->getSession()->isLogged()) {
                header('Location: ../user');
                exit();
            } else {
                header('Location: ../index');
                exit();
            }
        }
        
    }
    
    function editself(){
        if (!$this->getSession()->isLogged()) {
           header('Location: ../index');
            exit;
        }
        $usuario = $this->getSession()->getLogin();
        $this->getModel()->set('user_data', $usuario);
        $this->getModel()->set('twigFile', '_editself.html');
    }
    
    function doeditself(){
        if (!$this->getSession()->isLogged()) {
           header('Location: ../index');
            exit;
        }
        
        $id = Reader::read('id');
        $resultado = $this->getModel()->doeditself($id);
        
        header('Location: admin/usuarios?resultado=' . $resultado);
        exit;
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
    
    function links() {
        //1º control de sesión
        if($this->getSession()->isLogged()) {
            $this->getModel()->set('twigFile', '_links.html');
            $this->getModel()->set('user', $this->getSession()->getLogin()->getCorreo());
        } else {
            header('Location: ../index');
            exit(); 
        }
    }

}