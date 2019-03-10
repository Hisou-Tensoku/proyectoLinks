<?php
namespace izv\controller;

use izv\app\App;
use izv\data\User;
use izv\model\Model;
use izv\tools\Session;
use izv\tools\Reader;
use izv\tools\Tools;

/**
 * El controlador
 * 
 */
class DashController extends Controller {

    /**
     * Opcional si se quiere añadir algo
     * 
     */
    function __construct(\izv\model\Model $model) {
        parent::__construct($model);
    }
    
    private function checkLogin() {
        if(!$this->getSession()->isLogged()) {
            $this->location('user/index', 'session');
        }
    }
    
    private function loadUser() {
        $this->checkLogin();
        $id = $this->getSession()->getLogin();
        
        $user = $this->getModel()->getUser($id->getId());
        
        if($user->getPicture() !== null) {
            $picture =  $user->getPicture();
            $this->getModel()->set('user_img', 'data:image/png;base64,' . stream_get_contents($picture));//stream_get_contents
        }
        $this->getModel()->set('user', $user);
    }
    
    // -- Acciones -- 
    function action() {
        $this->loadUser();
        
        $pagina = Reader::read('page');
        if($pagina === null || !is_numeric($pagina)) {
            $pagina =1;
        }
        
        $orden = Reader::read('order');
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
        $filter = Reader::read('filter');
        
        //$r = $this->getModel()->getUsers($pagina, $orden, $filter);
        //$this->getModel()->add($r);
        
        $this->getModel()->set('users', $this->getModel()->getUsersList());
        
        $this->getModel()->set('is_root', $this->getSession()->isRoot());
        $this->getModel()->set('template_file', 'index.twig');
        $this->getModel()->set('title', 'Dashboard');
    }
    
    function projects() {
        $this->loadUser();
        
        $pagina = Reader::read('page');
        if($pagina === null || !is_numeric($pagina)) {
            $pagina =1;
        }
        
        $orden = Reader::read('order');
        $ordenes = array('id', 'correo', 'alias', 'nombre', 'date');
        if(!isset($ordenes[$orden])) {
            $orden='id';
        }
        $filter = Reader::read('filter');
        
        $r = $this->getModel()->getProjects($this->getSession()->getLogin()->getId(), $pagina, $orden, $filter);
        $this->getModel()->add($r);
        
        $this->getModel()->set('template_file', 'project.twig');
        $this->getModel()->set('title', 'Dashboard');
    }
    
    function profile() {
        $this->loadUser();
        $this->getModel()->set('title', 'Profile');
        $this->getModel()->set('template_file', 'profile.html');
    }
    
    function map() {
        $this->loadUser();
        $this->getModel()->set('title', 'Maps');
        $this->getModel()->set('template_file', 'map.html');
    }
    
    function doeditself() {
        $this->checkLogin();
        $user = $this->getSession()->getLogin();
        $id = Reader::read('id');
        $r = 0;
        if($id ===  $user->getId()) {
            $alias = Reader::read('alias');
            $nombre = Reader::read('nombre');
            
            if(isset($alias) && isset($nombre)) {
                $user->setNickname($alias);
                $user->setName($nombre);
                
                if($this->getModel()->update()) {
                    $r = 1;
                }
            }
        }
        header('Location: '.App::BASE.'dashboard/profile?a=edit&r='.$r);
        exit();
    }
    
    function editpicture() {
        $this->checkLogin();    
        $user = $this->getSession()->getLogin();
        $id = Reader::read('id');
        $r = 0;
        if($id ===  $user->getId() && isset($_FILES["fichero"])) {
            if ($_FILES["fichero"]["error"] <= 0){
            	$bin_string = file_get_contents($_FILES["fichero"]["tmp_name"]);
            	$picture = base64_encode($bin_string);
                
                if($this->getModel()->editPicture($id, $picture)) {
                    $r = 1;
                }
            } else 
                echo 'ERROR SUBIDA DE ARCHIVO';
        }
        header('Location: '.App::BASE.'dashboard/profile?a=edit&r='.$r);
        exit();
    }
    
    function editmail() {
        $this->checkLogin();
        $user = $this->getSession()->getLogin();
        $id = Reader::read('id');
        if($id ===  $user->getId()) {
            $correo = Reader::read('correo');
            
            if(isset($correo) && $this->getModel()->editCorreo($user, $correo) ) {
                $this->getSession()->logout();
                header('Location: '.App::BASE.'user/login');
                exit();
            }
        }
        header('Location: '.App::BASE.'dashboard/profile?a=edit');
        exit();
    }
    
    function editpasswd() {
        $this->checkLogin();
        $user = $this->getSession()->getLogin();
        $id = Reader::read('id');
        if($id ===  $user->getId()) {
            $clave = Reader::read('clave');
            $claveNew = Reader::read('claveNew');
            $claveNew2 = Reader::read('claveNew2');
            
            if(isset($claveNew) && isset($claveNew2) && $claveNew === $claveNew2 ) {
                if($this->getModel()->editPasswd($user, $clave, $claveNew)) {
                    $this->getSession()->logout();
                    header('Location: '.App::BASE.'user/login');
                    exit();
                }
            }
        }
        header('Location: '.App::BASE.'dashboard/profile?a=edit');
        exit();
    }
    
    function dodestruct() {
        $this->checkLogin();
        $user = $this->getSession()->getLogin();
        $id = Reader::read('id');
        if($id ===  $user->getId()) {
            $partial = Reader::read('partial');
            
            if(isset($partial) && is_numeric($partial) ) {
                if($this->getModel()->doDestruct($user, $partial)) {
                    $this->getSession()->logout();
                    header('Location: '.App::BASE.'user/login');
                    exit();
                }
            }
        }
        header('Location: '.App::BASE.'dashboard/profile?a=edit');
        exit();
    }
    
    private function checkLoginRoot() {
        if(!$this->getSession()->isLogged() || !$this->getSession()->isRoot()) {
            header('Location: '.App::BASE.'dashboard?a=sesion');
            exit();
        }
    }
    
    function insert() {
        $this->checkLoginRoot();
        $this->getModel()->set('template_file', 'insert.html');
        $this->getModel()->set('title', 'Insert');
    }
    
    function doinsert() {
        $this->checkLoginRoot();
        $r=0;
        $user = Reader::readObject('izv\data\User');
        
        if(isset($user) && $this->getModel()->insert($user) ) {
            $r = 1;
        }
        header('Location: '.App::BASE.'dashboard/insert?a=insert&r='.$r);
        exit();
    }
    
    function edit() {
        $this->checkLoginRoot();
        $id = Reader::read('id');
        if(!isset($id)) {
            header('Location: '.App::BASE.'dashboard');
            exit();
        }
        $user = $this->getModel()->getUser($id);
        if(!isset($user)) {
            header('Location: '.App::BASE.'dashboard');
            exit();
        }
        $this->getModel()->set('user_edit', $user);
        
        $this->getModel()->set('template_file', 'update.html');
        $this->getModel()->set('title', 'Edit');
    }
    
    function doedit() {
        $this->checkLoginRoot();
        $r=0;
        $user = Reader::readObject('izv\data\User');
        if(isset($user)) {
            if($user->getPassword() !== null) {
                $user->setPassword(Tools::encriptar($user->getPassword()));
            }
            if($this->getModel()->editUser($user)) {
               $r = 1; 
            }
        }
        header('Location: '.App::BASE.'dashboard?a=edit&r='.$r);
        exit();
    }
    
    function dodelete() {
        $this->checkLoginRoot();
        $r=0;
        $id = Reader::read('id');
        $ids = Reader::readArray('ids');
        if(isset($id)) {
            if($this->getModel()->deleteUser($id)) {
               $r = 1;
            }
        } else if($ids) {
            if($this->getModel()->deleteUsers($ids)>0) {
               $r = 1;
            }
        }
        header('Location: '.App::BASE.'dashboard?a=delete&r='.$r);
        exit();
    }
    
}