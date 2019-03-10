<?php
namespace izv\model;

use izv\database\Doctrine;

/**
 * El modelo siempre accede a la base de datos
 * Luego hay que automatizar esos accesos
 * 
 * 
 */
class DoctrineModel extends Model {

    private $doc, $viewData = array();

    function __construct() {
        $this->doc = new Doctrine();
        $this->viewData = array();
    }
    
    function getDoctrine() {
        return $this->doc;
    }
    
    function __destruct() {}
    
    function insert($class) {
        $gestor = $this->getDoctrine()->getEntityManager();
        
        try {
            $gestor->persist($class);
            $gestor->flush();
            return true;
        } catch(Exception $e) {
            //echo $e->getMessage();
            return false;
        }
    }
    
    
    function edit($id, $class, $data) {
        $r = false;
        $gestor = $this->getDoctrine()->getEntityManager();
        try {
            $obj = $gestor->find($class, $id);
            
            if(isset($obj)) {
                $obj->set($data);
                $gestor->flush();
                $r = true;
            }
            
        } catch(Exception $e) {
            //echo $e->getMessage();
            $r =  false;
        }
        return $r;
    }
    
    function delete($id, $class) {
        $gestor = $this->getDoctrine()->getEntityManager();
        try {
            $project = $gestor->find($class, $id);
            
            $gestor->remove($project);
            $gestor->flush();
            return true;
        } catch(Exception $e) {
            return false;
        }
    }
    
}