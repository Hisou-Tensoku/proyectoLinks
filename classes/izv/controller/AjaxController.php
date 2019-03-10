<?php

namespace izv\controller;

use izv\app\App;
use izv\data\Usuario;
use izv\model\Model;
use izv\tools\Reader;
use izv\tools\Session;
use izv\tools\Util;
use izv\tools\Upload;

class AjaxController extends Controller {

    function __construct(Model $model) {
        parent::__construct($model);
        //...
    }
    
    function comprobaralias() {
        $alias = Reader::read('alias');
        $available = 0;
        if($alias !== null && $alias !== '') {
            $available = $this->getModel()->aliasAvailable($alias);
        }
        $this->getModel()->set('aliasdisponible', $available);
    }
    
    function comprobarcorreo() {
        $correo = Reader::read('correo');
        $available = 0;
        if(filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $available = $this->getModel()->correoAvailable($correo);
        }
        $this->getModel()->set('correodisponible', $available);
    }
    
    function dologin(){
        $correo = Reader::read('correo');
        $clave = Reader::read('clave');
        $resultado = $this->getModel()->login($correo, $clave);
        if($resultado !== false) {
            $this->getSession()->login($resultado);
            $this->listaciudades();
            $resultado=true;
        }
        $this->getModel()->set('login', $resultado);
    }

    function dologout(){
        $this->getSession()->logout();
        $this->getModel()->set('logout', true);
    }

    function listalinks() {
        $pagina = Reader::read('pagina');
        if($pagina === null || !is_numeric($pagina)) {
            $pagina = 1;
        }
        $orden = Reader::read('orden');
        $ordenes = array(
            'id' => 'id',
            'descripcion' => 'descripcion',
            'href' => 'href',
            'categoria' => 'categoria'
        );
        if(!isset($ordenes[$orden])) {
            $orden='id';
        }
        $filtro = Reader::read('filtro');
        $idusuario = $this->getSession()->getIDUsuario();
        $r = $this->getModel()->getEnlaces($idusuario, $pagina, $orden, $filtro);
        $this->getModel()->add($r);
    }
    
    function listalinksporcategoria() {
        $pagina = Reader::read('pagina');
        if($pagina === null || !is_numeric($pagina)) {
            $pagina = 1;
        }
        $categoria = Reader::read('categoria');

        $idusuario = $this->getSession()->getIDUsuario();
        $r = $this->getModel()->getEnlacesPorCategoria($idusuario, $pagina, $categoria);
        $this->getModel()->add($r);
    }
    
    function listacategorias() {
        $idusuario = $this->getSession()->getIDUsuario();
        $r = $this->getModel()->getCategorias($idusuario);
        $this->getModel()->add($r);
    }
    
    function main() {
        $this->listalinks();
        $this->listacategorias();
    }
    
    function prueba() {
        $idusuario = $this->getSession()->getIDUsuario();
        $r = $this->getModel()->getEnlaces($idusuario);
        //echo Util::varDump($r);
    }
    
    function registercategoria() {
        $categoria = Reader::readObject("izv\data\Categoria");
        $idusuario = $this->getSession()->getIDUsuario();
        $usuario = $this->getModel()->getUsuario($idusuario);
        $categoria->setUsuario($usuario);

        $resultado = $this->getModel()->addObjeto($categoria);
        $this->getModel()->set('alta', $resultado);
    }
    
    function registerenlace() {
        $enlace = Reader::readObject("izv\data\Link");
        $idusuario = $this->getSession()->getIDUsuario();
        $usuario = $this->getModel()->getUsuario($idusuario);
        $enlace->setUsuario($usuario);
        $idcategoria = $enlace->getCategoria();
        $catego = $this->getModel()->getCategoriaIndividual($idcategoria);
        $enlace->setCategoria($catego);
        $resultado = $this->getModel()->addObjeto($enlace);
        $this->getModel()->set('alta', $resultado);
    }
    
    function eliminaenlace() {
        $id = Reader::read('id');
        $resultado = 0;
        if ($id !== null) {
            $resultado = $this->getModel()->eliminEnlace($id);
        }
        $this->getModel()->set('delete', $resultado);
    }
    
}