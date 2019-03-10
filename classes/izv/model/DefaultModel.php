<?php

namespace izv\model;

use izv\data\City;
use izv\data\Usuario;
use izv\database\Database;
use izv\managedata\ManageCity;
use izv\managedata\ManageUsuario;
use izv\tools\Pagination;
use izv\tools\Util;

class DefaultModel extends Model {

    function activar($code, $id) {
        $entityManager = $this->getDatabase();
        $resultado = 0;
        if($id !== null && $code !== null) {
        
            $usuario = $entityManager->getRepository('izv\data\Usuario')
                        ->findOneBy(array('id' => $id));
            }
            if($usuario !== null && !$usuario->getActivo()) {
                $usuario->setActivo(true);
                $resultado = 1;
                $entityManager->flush();
            }
        return $resultado;
    }

    function login($correo, $clave) {
        $entityManager = $this->getDatabase();
        $resultado = false;
        $sql = 'select u from izv\data\Usuario u where u.correo = :correo and u.activo = 1';
        $sentencia = $entityManager->createQuery($sql);
        $sentencia->setParameter('correo', $correo);
        $r = $sentencia->getResult();
        if(count($r) > 0) {
            $usuario = $r[0];
            if(Util::verificarClave($clave, $usuario->getClave())){
                $usuario->setClave('');
                $resultado = $usuario;
            }
        }
        return $resultado;
    }

    function register(Usuario $usuario) {
        $entityManager = $this->getDatabase();
        $entityManager->persist($usuario);
        $entityManager->flush();
        return $usuario->getId();
    }
    
}