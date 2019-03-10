<?php
namespace izv\model;

use izv\database\Database;
use izv\tools\Mail;
use izv\app\App;
use izv\tools\Tools;
use izv\tools\Pagination;
use izv\data\Project;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * El modelo siempre accede a la base de datos
 * Luego hay que automatizar esos accesos
 * 
 */
class DashModel extends UserModel {
    
    function pagination($class, $total, $pagina=1, $orden = 'id', $filtro = '', $limit = 10) {
        $gestor = $this->getDoctrine()->getEntityManager();
        
        $names = $gestor->getClassMetadata($class)->getColumnNames();
        $query = $gestor->createQueryBuilder();
        
        // Set select class & from
        $query->select('c');
        $query->from($class, 'c');
        // Add orders
        $query->addOrderBy($orden, 'DESC');
        // Add search 
        if(isset($filtro) && trim($filtro) !== '') {
            foreach($names as $n => $name) {
                if($n == 0) {
                    $query->where('? LIKE :filtro', $name);
                } else
                    $query->andWhere('? LIKE :filtro', $name);
            }
        }
        
        
        /* -- The proccess of creative --
        $dql = 'select p from izv\data\Project p where p.author_id = :user order by p.'. $orden .', p.date, p.title, p.content';
        ->from('Project\Entities\Item', 'i')
            ->select("i, e")
            ->join("i.entity", 'e')
            ->where("i.lang = :lang AND e.album = :album")
            ->setParameter('lang', $lang)
            ->setParameter('album', $album);
        
        $expr = $em->getExpressionBuilder();
        
        if(isset($filtro) && trim($filtro) !== '') {
            $dql = 'select p from izv\data\Project p 
                    where p.active = 1 and id like :filtro or u.title like :filtro or u.excerpt like '.$filtro.' or u.date like '.$filtro .'
                    order by p.'. $orden .', p.date, p.title, p.excerpt';
            $query = $gestor->createQuery($dql);
            $query->setParameter('filtro', $filtro);
        }
        //$exp =  $qb->expr()->in('r.winner', array('?1'))
        
        //$query->add('where', $exp);
        ->where('e.source_id = :id')
        ->andWhere('source_name=?', 'test')
        ->andWhere('source_val=?', '30')
       
        //$query->orderBy('column1 ASC, column2 DESC');

        //$query->addOrderBy('column1', 'ASC')
        //   ->addOrderBy('column2', 'DESC');
        */
        $paginacion = new Pagination($total, $pagina, $limit);
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($pagina - 1))
            ->setMaxResults($limit);
        
        return array(
            'paginator' => $paginator,
            'rango' => $paginacion->rango(),
            'pagination' => $paginacion->values(),
            'order' => $orden
        );
    }
    
    function getProjects($user_id, $pagina=1, $orden = 'id', $filtro = '', $limit = 10) {
        $total = $this->getNumProjects($user_id);
        
        $r = $this->pagination('izv\data\projects', $total, $pagina, $orden, $filtro, $limit);
        
        $paginator = $r['paginator'];
        $projects = array();
        foreach($paginator as $project) {
            if($project->getActive()) {
                $array = $project->get();
                $array['date'] = $project->getDate()->format('d-m-Y');
                $array['image'] = stream_get_contents($project->getImage());
                $projects[] = $array;
            }
        }
        
        unset($r['paginator']);
        $r['projects'] = $projects;
        
        return $r;
    }
    
    function getNumProjects($user_id) {
        $gestor = $this->getDoctrine()->getEntityManager();
        $user = $gestor->find('izv\data\User', $user_id);
        return count($user->getProjects());
    }
    
    function addUser($user) {
        // Encriptamos la clave
        $user->setClave(Tools::encriptar($user->getClave()));
        return $this->add($user);
    }
    
    function editCorreo($user, $newCorreo) {
        try {
            $oldCorreo = $user->getCorreo();
            // Cambiamos correo
            $user->setCorreo($newCorreo);
            $user->setActivo(false);
            // Actualizamos
            $this->getDoctrine()->getEntityManager()->flush();
            // Enviamos emails
            Mail::sendActivation($user, App::BASE . 'user/activate');
            Mail::sendMailChange($user, $oldCorreo);
            
            return true;
        } catch(Exception $e) {
            //echo $e->getMessage();
            return false;
        }
    }
    
    function editPasswd($user, $clave, $newClave) {
        try {
            // Comprobamos contraseña
            if(!Tools::verificarClave($clave, $user->getClave()))
                return false;
                
            $user->setClave(Tools::encriptar($newClave));
            $user->setActivo(false);
            // Actualizamos
            $this->getDoctrine()->getEntityManager()->flush();
            // Enviamos emails
            Mail::sendActivation($user, App::BASE . 'user/activate');
            
            return true;
        } catch(Exception $e) {
            //echo $e->getMessage();
            return false;
        }
    }
    
    function doDestruct($user, $partial) {
        try {
            $gestor = $this->getDoctrine()->getEntityManager();
            if($patial) {
                $user->setActivo(false);    
                Mail::sendActivation($user, App::BASE . 'user/activate');
            } else {
                $gestor->remove($user);
            }
            $gestor->flush();
            return true;
        } catch(Exception $e) {
            //echo $e->getMessage();
            return false;
        }
    }
    
    
    function deleteMultiple($ids, $class) {
        $r = 0;
        foreach($ids as $id) {
            if($this->delete($id, $class) ) {
                $r++;
            }
        }
        return $r;
    }
    
    
    
    function editUser($tmp) {
        $gestor = $this->getDoctrine()->getEntityManager();
        // Sacamamos user
        $user = $gestor->find('izv\data\User', $tmp->getId());
        if(isset($user)) {
            if($tmp->getCorreo() !== null) {
                $user->setCorreo($tmp->getCorreo());
            }
            if($tmp->getAlias() !== null) {
                $user->setAlias($tmp->getAlias());
            }
            if($tmp->getClave() !== null) {
                $user->setClave($tmp->getClave());
            }
            if($tmp->getActivo() !== null) {
                $user->setActivo($tmp->getActivo());
            }
            if($tmp->getAdministrador() !== null) {
                $user->setAdministrador($tmp->getAdministrador());
            }
            try {
                $gestor->flush();
                return true;
            } catch(Exception $e) {
                //echo $e->getMessage();
                
            } 
        }
        return false;
    }
    
    function editPicture($id, $picture) {
        $gestor = $this->getDoctrine()->getEntityManager();
        try {
            $user = $gestor->find('izv\data\User', $id);
            
            $user->setPicture($picture);
            
            $gestor->flush();
            return true;
        } catch(Exception $e) {
            //echo $e->getMessage();
            return false;
        }
    }
    
    
}