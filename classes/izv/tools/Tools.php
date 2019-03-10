<?php

namespace izv\tools;

class Tools {

    static function view($any) {
        echo self::varDump($any);
    }
    
    static function varDump($any) {
        return '<pre>' . var_export($any, true) . '</pre>';
    }
    
    // Nombre mejor printHTML, but I <3 Python :)
    static function print($cad, $tag='p') {
        return '<'. $tag .'>'. $cad . '</'. $tag .'>';
    }
    
    static function formatDate($date, $format='d-m-Y H:i:s') {
        $date = new \DateTime($date);
        return $date->format($format);
    }
    
    static function url() {
        $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $parts = pathinfo($url);
        return $parts['dirname'] . '/';
    }
    
    static function getFullUrl($route, $params=null) {
        $url = self::url() . $route;
        if($params !== null && is_array($params)) {
            $url .= '?';
            foreach ($params as $i => $value) {
                $url .= urlencode($i) . '=' . urlencode($value);
                if($i < count($params)-1) {
                    $url .= '&';
                }
            }
        }
    }
    
    static function encriptar($cadena, $coste = 10) {
        $opciones = array(
            'cost' => $coste
        );
        return password_hash($cadena, PASSWORD_DEFAULT, $opciones);
    }
    
    static function encryptJWT($cadena, $clave) {
        return \Firebase\JWT\JWT::encode( $cadena, $clave);
    }
    
    static function decryptJWT($cadena, $clave) {
        return \Firebase\JWT\JWT::decode($cadena, $clave, array('HS256'));
    }
    
    static function verificarClave($claveSinEncriptar, $claveEncriptada) {
        return password_verify($claveSinEncriptar, $claveEncriptada);
    }
    
    static function verificarCaptcha($captcha, $secret) {
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret. '&response='. $captcha);
        
        $response = json_decode($response, true);
        return $response["success"];
    }
    
    
}
