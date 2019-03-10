<?php

namespace izv\tools;

/**
 * Subida de multiples archivos
 * Nueva implementacion del Upload
 * - Novedades:
 *  - Añadidas 's' a las varibles
 *  - Nuevos parametros index
 *  - Nuevo bucle
 * 
 */
class UploadMultiple {
    // Constantes
    const POLICY_KEEP = 1,
            POLICY_OVERWRITE = 2,
            POLICY_RENAME = 3;

    const UPLOAD_ERR_OK = 0,
            UPLOAD_ERR_NAME = 1,
            UPLOAD_ERR_SIZE = 2,
            UPLOAD_ERR_FILE = 8,
            UPLOAD_ERR_TYPE = 9,
            UPLOAD_ERR_MOVE = 10,
            UPLOAD_ERR_POLICY = 11;
    
    // Atirbutos o Variables de Instancia
    private $files,
        $names,
        $savedNames,
        $policy = self::POLICY_KEEP,
        $error,
        $errorFiles,
        $maxSize, 
        $target='./',
        $type = '';
    
    // Constructor
    function __construct($input) {
        $this->error = self::UPLOAD_ERR_FILE;
        if(isset($_FILES[$input])){
            $this->files = $_FILES[$input];
            $this->errorFiles = $_FILES[$input]['error'];
            $this->error = self::UPLOAD_ERR_OK;
            $this->names = $this->files['name'];
        }
        
    }
    
    // Metodos (reciclados por mi compromiso con el medio ambiente :)
    
    
    // --- En Construccion ---
    function uploadAll() {
        $result = 0;
        if($this->error !== 1) {
            foreach($this->files['name'] as $i=>$file){
                if($this->uploadFile($i)){
                    $result++;
                }
            }
        }
        return $result;
    }
    
    function uploadFile($index){
        $result = false;
        if($this->files['error'][$index]=== 0){
            $this->errorFiles[$index] = $this->isValidSize($index)? 0: self::UPLOAD_ERR_SIZE;
            $this->errorFiles[$index] = $this->isValidType($index)? 0: self::UPLOAD_ERR_TYPE;
            if($this->errorFiles[$index] === 0){
                $result = $this->__doUpload($index);
            }    
        }else{
            $this->error = 2;
        }
        return $result;
    }
    
    // Upload aislados
    
    private function __doUpload($index) {
        $result = false;
        switch($this->policy){
            case self::POLICY_KEEP:
                $result=$this->__doUploadKeep($index);
                break;
            case self::POLICY_OVERWRITE:
                $result=$this->__doUploadOverwrite($index);
                break;
            case self::POLICY_RENAME:
                $result=$this->__doUploadRename($index);
                break;
        }
        if(!$result && $this->error == 0){
            $this->error = 3;
        }
        return $result;
    }
    
    private function __doUploadKeep($index){
        $result = false;
        if(file_exists($this->target . $this->names[$index]) === false) {
            $result = move_uploaded_file($this->files['tmp_name'][$index], $this->target . $this->names[$index]);
        }    
        return $result;
    }
    
    private function __doUploadOverwrite($index){
        return move_uploaded_file($this->files['tmp_name'][$index], $this->target . $this->names[$index]);
    }
    
    private function __doUploadRename($index){
        $newName = $this->target . $this->names[$index];
        if(file_exists($newName)) {
            $newName = self::__getValidName($newName);
        }
        $result = move_uploaded_file($this->files['tmp_name'][$index], $newName);
        if($result) {
            $nombre = pathinfo($newName);
            $nombre = $nombre['basename'];
            $this->savedNames[$index] = $nombre;
        }
        return $result;
    }
    
    // Funciones
    
    private static function __getValidName($file) {
        $parts = pathinfo($file);
        $extension = '';
        if(isset($parts['extension'])) {
            $extension = '.' . $parts['extension'];
        }
        $cont = 0;
        while(file_exists($parts['dirname'] . '/' . $parts['filename'] . $cont . $extension)) {
            $cont++;
        }
        return $parts['dirname'] . '/' . $parts['filename'] . $cont . $extension;
    }
    
    function isValidSize($index) {
        return ($this->maxSize === 0 || $this->maxSize >= $this->files['size'][$index]);
    }
    
    function isValidType($index){
        $valid = true;
        if($this->type !== ''){
            $tipo = shell_exec('file --mime ' . $this->files['tmp_name'][$index]);
            $pos = strpos($tipo, $this->type);
            if($pos === false){
                $valid = false;
            }
        }
        return $valid;
    }
    
    // Get Y Set
    
    function getNames() {
        $nombres = $this->savedNames;
        if(count($nombres)===0){
            $nombres = $this->names;
        }
        return $nombres;
    }
    
    function getFileCount() {
        return count($this->files['name']);
    }

    function getError() {
        $error = $this->error;
        if($error >  8){
            $error = $this->files['error'];
        }
        return $error;
    }
    
    function setName($name) {
        if(is_string($name) && trim($name)!== ''){
            $nombre = trim($name);
            $parts = pathinfo($nombre);
            $extension = '';
            if(isset($parts['extension'])) {
                $extension = '.' . $parts['extension'];
            }
            foreach($this->names as $i=>$file){
                $this->names[$i] = $parts['dirname'] . '/' . $parts['filename'] . $i . $extension;    
            }
        }
        return $this;
    }

    function setPolicy($policy) {
        if(is_int($policy) && $policy >=1 && $policy <=3){
            $this->policy = $policy;
        }
        return $this;
    }

    function setSize($size) {
        if(is_numeric($size) && $size > 0){
            $this->maxSize = $size;
        }
        return $this;
    }

    function setTarget($target) {
        if(is_string($target) && trim($target)!== ''){
            $this->target = trim($target);
        }
        return $this;
    }
    
    function setType($type) {
        if(is_string($type) && trim($type) !== '') {
            $this->type = trim($type);
        }
        return $this;
    }
    
}


/* -- First Again :) -- Not Mine -- Css & Js take time :W

class UploadFiles {

    const POLICY_KEEP = 1,
            POLICY_OVERWRITE = 2,
            POLICY_RENAME = 3;

    private $error = false,
            $files,
            $maxSize = 0,
            $name = '',
            $policy = self::POLICY_OVERWRITE,
            $savedNames = array(),
            $target = './',
            $type = '';

    function __construct($input) {
        if(isset($_FILES[$input])) {
            $this->files = $_FILES[$input];
        } else {
            $this->error = true;
        }
    }

    private function __doUpload($file, $index) {
        $result = false;
        switch($this->policy) {
            case self::POLICY_KEEP:
                $result = $this->__doUploadKeep($file, $index);
                break;
            case self::POLICY_OVERWRITE:
                $result = $this->__doUploadOverwrite($file, $index);
                break;
            case self::POLICY_RENAME:
                $result = $this->__doUploadRename($file, $index);
                break;
        }
        return $result;
    }

    private function __doUploadKeep($file, $index) {
        $result = false;
        $name = $this->__getFileName($file);
        if(!file_exists($this->target . $name)) {
            $result = $this->__move($file, $this->target . $name, $index);
        } else {
            $this->savedNames[$index] = 'policy error';
        }
        return $result;
    }

    private function __doUploadOverwrite($file, $index) {
        $name = $this->__getFileName($file);
        $result = $this->__move($file, $this->target . $name, $index);
        return $result;
    }

    private function __doUploadRename($file, $index) {
        $name = $this->__getFileName($file);
        $newName = $this->target . $name;
        if(file_exists($newName)) {
            $newName = self::__getValidName($newName);
        }
        $result = $this->__move($file, $newName, $index);
        return $result;
    }

    private function __getFileName($file) {
        $name = $file['name'];
        if($this->name !== '') {
            $name = $this->name;
        }
        return $name;
    }

    private function __getOrderedFiles() {
        $files = array();
        $names = $this->files['name'];
        if(is_array($names)) {
            $files = $this->__reOrder($this->files);
        } else {
            $files[] = $this->files;
        }
        return $files;
    }

    private static function __getValidName($file) {
        $parts = pathinfo($file);
        $extension = '';
        if(isset($parts['extension'])) {
            $extension = '.' . $parts['extension'];
        }
        $cont = 0;
        while(file_exists($parts['dirname'] . '/' . $parts['filename'] . $cont . $extension)) {
            $cont++;
        }
        return $parts['dirname'] . '/' . $parts['filename'] . $cont . $extension;
    }

    private function __move($file, $name, $index) {
        $result = move_uploaded_file($file['tmp_name'], $name);
        if($result) {
            $nameParts = pathinfo($name);
            $this->savedNames[$index] = $nameParts['basename'];
        } else {
            $this->savedNames[$index] = 'move error';
        }
        return $result;
    }

    private static function __reOrder(array $array) {
        $ordered = array();
        foreach($array as $key => $all) {
            foreach($all as $index => $value) {
                $ordered[$index][$key] = $value;
            }
        }
        return $ordered;
    }

    private function __uploadFiles($files) {
        $result = 0;
        foreach($files as $index => $file) {
            if($file['error'] === 0 && $this->isValidSize($file) && $this->isValidType($file)) {
                if($this->__doUpload($file, $index)) {
                    $result++;
                }
            } else {
                $this->savedNames[$index] = 'upload, size or type error';
            }
        }
        return $result;
    }

    function getError() {
        return $this->error;
    }

    function getMaxSize() {
        return $this->maxSize;
    }

    function getNames() {
        return $this->savedNames;
    }

    function isValidSize($size) {
        return ($this->maxSize === 0 || $this->maxSize >= $size);
    }

    function isValidType($file) {
        $valid = true;
        if($this->type !== '') {
            $type = shell_exec('file --mime ' . $file);
            $posicion = strpos($type, $this->type);
            if($posicion === false) {
                $valid = false;
            }
        }
        return $valid;
    }

    function setMaxSize($size) {
        if(is_int($size) && $size > 0) {
            $this->maxSize = $size;
        }
        return $this;
    }

    function setName($name) {
        if(is_string($name) && trim($name) !== '') {
            $this->name = trim($name);
        }
        return $this;
    }

    function setPolicy($policy) {
        if(is_int($policy) && $policy >= self::POLICY_KEEP && $policy <= self::POLICY_RENAME) {
            $this->policy = $policy;
        }
        return $this;
    }

    function setTarget($target) {
        if(is_string($target) && trim($target) !== '') {
            $this->target = trim($target);
        }
        return $this;
    }

    function setType($type) {
        if(is_string($type) && trim($type) !== '') {
            $this->type = trim($type);
        }
        return $this;
    }

    function upload() {
        $result = 0;
        if(!$this->error) {
            $files = $this->__getOrderedFiles();
            $result = $this->__uploadFiles($files);
        }
        return $result;
    }

}

*/