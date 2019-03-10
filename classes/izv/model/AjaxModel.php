<?php

namespace izv\model;

use Doctrine\ORM\Tools\Pagination\Paginator;

use izv\data\Playlist;
use izv\database\Database;
use izv\managedata\Bootstrap;
use izv\tools\Pagination;
use izv\tools\Session;
use izv\data\Usuario;

class AjaxModel extends Model {

    function addObjeto($objeto) {
        try {
            $gestor = $this->getDatabase();
            $gestor->persist($objeto);
            $gestor->flush();
            return $objeto->getId();
        } catch(\Exception $e) {
            return 0;
        }
    }
    
    function prueba($usuario) {
        //echo $this->getSession()->getIDUsuario();
        echo $usuario;
    }

    function getEnlaces($idusuario, $pagina = 1, $orden = 'id', $filtro=null, $limit = 5) {
        $gestor = $this->getDatabase();
        
        if ($filtro===null || trim($filtro) === '') {
            $dql = 'select c from izv\data\Link c where c.usuario = '. $idusuario .' order by c.'. $orden .', c.href, c.descripcion, c.id';

        } else {
            $filtro = '%'.$filtro.'%';
            $dql = "select c from izv\data\Link c
                    where (c.id like '".$filtro."' or c.href like '".$filtro."' or 
                    c.descripcion like '".$filtro."') and c.usuario = ". $idusuario ."  order by c.". $orden .", c.href, c.descripcion, c.id";

        }
        
        
        
        $query = $gestor->createQuery($dql);
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($pagina - 1))
            ->setMaxResults($limit);
        $pagination = new Pagination($paginator->count(), $pagina, $limit);
        //return $paginator;
        $listaenlaces = array();
        foreach($paginator as $enlace) {
            $enlacetemporal = $enlace->get();
            $enlacetemporal['categoria'] = $enlace->getCategoria()->get();
            $listaenlaces[] = $enlacetemporal;
        }
        return ['enlaces' => $listaenlaces, 'paginas' => $pagination->values()];
    }
    
    function getEnlacesPorCategoria($idusuario, $pagina = 1, $categoria, $limit = 5) {
        $gestor = $this->getDatabase();
        $dql = 'select c from izv\data\Link c where c.usuario = '. $idusuario .' and c.categoria = '. $categoria .'';
        $query = $gestor->createQuery($dql);
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($pagina - 1))
            ->setMaxResults($limit);
        $pagination = new Pagination($paginator->count(), $pagina, $limit);
        //return $paginator;
        $listaenlaces = array();
        foreach($paginator as $enlace) {
            $enlacetemporal = $enlace->get();
            $enlacetemporal['categoria'] = $enlace->getCategoria()->get();
            $listaenlaces[] = $enlacetemporal;
        }
        return ['enlaces' => $listaenlaces, 'paginas' => $pagination->values()];
    }
    
    function getCategorias($idusuario) {
        $gestor = $this->getDatabase();
        $categorias = $gestor->getRepository('izv\data\Categoria')
                      ->findBy(array('usuario' => $idusuario));
        $listacategorias = array();
        foreach($categorias as $categoria) {
            $listacategorias[] = $categoria->get();
        }
        return ['categoria' => $listacategorias];
    }
    
    function login($correo, $clave) {
        $gestor = $this->getDatabase();
        $usuario = $gestor->getRepository('izv\data\Usuario')->findOneBy(array('correo' => $correo));
        if($usuario !== null) {
            $resultado = \izv\tools\Util::verificarClave($clave, $usuario->getClave());
            if($resultado) {
                $usuario->setClave('');
                //Alejandro dixit
                $this->set('usuario', $usuario->get());
                return $usuario;
            }
        }
        return false;
    }
    
    function eliminEnlace($id) {
        try {
            $gestor = $this->getDatabase();
            $enlace = $gestor->getRepository('izv\data\Link')->find($id);
            $gestor->remove($enlace);
            $gestor->flush();
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    function getUsuario($id) {
        try {
            $gestor = $this->getDatabase();
            $usuario = $gestor->getRepository('izv\data\Usuario')->find($id);
            return $usuario;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    function getCategoriaIndividual($id) {
        try {
            $gestor = $this->getDatabase();
            $usuario = $gestor->getRepository('izv\data\Categoria')->find($id);
            return $usuario;
        } catch (\Exception $e) {
            return 0;
        }
    }

}