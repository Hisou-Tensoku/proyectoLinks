<?php
namespace izv\controller;

use izv\app\App;
use izv\model\Model;
use izv\tools\Session;

/**
 * El controlador
 * 
 */
class MainController extends Controller {
    
    // -- Basic Funtions --
    private function loadBasicTemplate($title, $template, $style) {
        // Template twig
        $this->getModel()->set('template_file', $template);
        $this->getModel()->set('template_css', $style);
        $this->getModel()->set('title', $title);
        
        // Datos
        $this->getModel()->set('is_logged', $this->getSession()->isLogged());
    }
    
    // -- Acciones -- 
    function action() {
        // Template twig
        $this->loadBasicTemplate('Index', 'index.twig', 'styleHome.css');
    }
    
    function projects() {
        // Template Twig
        $this->loadBasicTemplate('Projects', 'projects.twig', 'styleProjects.css');
        
        // Get elements  pagination? 
        
    }
    
    function links() {
        // Template Twig
        $this->loadBasicTemplate('Links', 'links.twig', 'styleLinks.css');
        
        // Get elements  pagination? 
        
    }
    
}