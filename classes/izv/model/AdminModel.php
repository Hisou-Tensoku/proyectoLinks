<?php

namespace izv\model;

use Doctrine\ORM\Tools\Pagination\Paginator;
use izv\app\App;
use izv\data\Usuario;
use izv\database\Database;
use izv\managedata\ManageUsuario;
use izv\tools\Pagination;
use izv\tools\Reader;
use izv\tools\Session;
use izv\tools\Util;

class AdminModel extends UserModel {

    function dodelete($id){
        $entityManager = $this->getDatabase()->getEntityManager();
        $resultado = 0;
        $usuario = $entityManager->find('izv\data\Usuario', $id);
            if($usuario !== null) {
                $entityManager->remove($usuario);
                $resultado = 1;
            }
            $entityManager->flush();
        
            return $resultado;
    }
    
    function dodeletetemporal($id){
        $entityManager = $this->getDatabase()->getEntityManager();
        $resultado = 0;
        $usuario = $entityManager->find('izv\data\Usuario', $id);
            if($usuario !== null) {
                $usuario->setActivo(false);
                $resultado = 1;
            }
            $entityManager->flush();
        
            return $resultado;
    }
    
    function getUsers($pagina = 1, $orden = 'id', $filtro = null) {
        $entityManager = $this->getDatabase()->getEntityManager();
        $total = $this->countUsers();
        
        if ($filtro === null) {
            $dql = 'select u from izv\data\Usuario u order by u.'. $orden .', 
            u.correo, u.alias, u.nombre, u.fechaalta';
        } else {
            $dql = "select u from izv\data\Usuario u
                    where u.id like '".$filtro."' or u.alias like '".$filtro."' or 
                    u.correo like '".$filtro."' or u.nombre like '".$filtro."' or 
                    u.fechaalta like '".$filtro."' order by u.". $orden .", 
                    u.correo, u.alias, u.nombre, u.fechaalta";
        }
        $paginacion = new Pagination($total, $pagina, 3);
        $offset = $paginacion->offset();
        $rpp = $paginacion->rpp();
        $query = $entityManager->createQuery($dql);
        $paginator = new Paginator($query);
        $paginator->getQuery()
                        ->setFirstResult($offset)
                        ->setMaxResults($rpp);
        $r = array();
        foreach($paginator as $user) {
            $r[] = $user->get();
        }
        return array(
            'usuarios' => $r,
            'rango' => $paginacion->range(),
            'paginas' => $paginacion->values(),
            'orden' => $orden
        );
    }
    
    function countUsers() {
        $entityManager = $this->getDatabase()->getEntityManager();
        $total = $entityManager->getRepository('izv\data\Usuario')->findAll();
        return count($total);
    }
    
    function getUser($id) {
        $entityManager = $this->getDatabase()->getEntityManager();
        return $entityManager->find('izv\data\Usuario', $id);
    }
    
    function doedit($id){
        $entityManager = $this->getDatabase()->getEntityManager();
        $resultado = 0;
        if($id !== null) {
            $user = $entityManager->getRepository('izv\data\Usuario')
                                ->findOneBy(array('id' => $id));
            if($user !== null) {
                $user->setCorreo(Reader::read('correo'));
                $user->setAlias(Reader::read('alias'));
                $user->setNombre(Reader::read('nombre'));
                $user->setActivo(Reader::read('activo'));
                $user->setAdministrador(Reader::read('administrador'));
                $clave = Reader::read('clave');
                if($clave!='') {
                    $user->setClave(Util::encriptar($clave));
                }
                $entityManager->flush();
                $resultado = 1;
            }
        }
        return $resultado;
    }
    
    function doeditself($id){
        $sesion = new Session(App::SESSION_NAME);
        $entityManager = $this->getDatabase()->getEntityManager();
        $resultado = 0;
        if($id !== null) {
        $user = $entityManager->getRepository('izv\data\Usuario')
                            ->findOneBy(array('id' => $id));
        if($user !== null) {
            $delete = Reader::read('bajaTmp');
            if($delete !== null) {
                if($delete) {
                    $user->setActivo(false);
                } else {
                    $entityManager->remove($user);
                }
                $entityManager->flush();
                $sesion->logout();
                header('Location: ../index');
                exit();
            } else {
                $user->setCorreo(Reader::read('correo'));
                $user->setAlias(Reader::read('alias'));
                $user->setNombre(Reader::read('nombre'));
                $clave = Reader::read('clave');
                if($clave!='') {
                    $user->setClave(Util::encriptar($clave));
                }
                $entityManager->flush();
                $resultado = 1;
                }
            }
        }
        return $resultado;
    }
}