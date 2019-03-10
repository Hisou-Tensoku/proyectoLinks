<?php

namespace izv\common;

/**
 * Trait es como un trozo de una clase
 * es una forma de implementar metodos comunes en una clase
 */
trait Comun{
    
    function introspeccion(){
        foreach($this as $atributo => $valor){
            echo $atributo .': '. $valor . '<br>';
        }
        
    }
    
    /**
     * metodoGet
     * 
     * @return Array asociativo cuyos indices son los atributos del objeto
     */
    function get(){
        $array = array();
        foreach($this as $atributo => $valor){
            //if(isset($valor)) {
                $array[$atributo] = $valor;    
            //}
        }
        return $array;
    }
    
    /**
     * metodoSet
     * Asignar el valor
     * 
     * @paran array asociativo cuyos indices son los atributos del objeto
     */
    function set(array $array){
        foreach($this as $atributo => $valor){
            $methodSet = 'set'.$atributo;
            if(isset($array[$atributo])){
                $this->$atributo = $array[$atributo];
            }
        }
        return $this;
    }
    
    // Mas lento
    function set2(array $array){
        foreach($array as $atributo => $valor){
            $methodSet = 'set'.$atributo;
            if(property_exists($this->$atributo)){
                $this->$atributo = $valor;
            }
        }
        return $this;
    }
    
    function __toString(){
        $cad = get_class().': ';
        foreach($this as $atributo => $valor){
            $cad .= $atributo .' = '. $valor . '<br>';
        }
        return $cad;
    }
    
    
    function fetch(array $array, $initial=0){
        if(count(get_object_vars($this)) <= count($array)){
            $i = $initial;
            foreach($this as $atributo => $valor){
                if($i<count($array)){
                    $this->$atributo = $array[$i];
                }
                $i++;
            }
        }
    }

    function fetchP(array $array, $initial=0){
        $i = $initial;
        foreach($this as $atributo => $valor){
            if(isset($array[$i])){
                $this->$atributo = $array[$i];
            }
            $i++;
        }
    }

    
}