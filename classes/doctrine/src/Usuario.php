<?php

namespace izv\data;

/**
 * @Entity @Table(name="usuario")
 * 
 */
class Usuario {
    
    /**
     * @Id
     * @Column(type="bigint") @GeneratedValue
     */
    private $id; 
    
    /**
     * @Column(type="string", length=255, unique=true, nullable=false)
     */
    private $correo;
    
    /**
     * @Column(type="string", length=255, unique=true, nullable=false)
     */
    private $alias;
    
    /**
     * @Column(type="string", length=255, unique=false, nullable=false)
     */
    private $nombre;
    
    /**
     * @Column(type="string", length=255, nullable=false)
     */
    private $clave;
    
    /**
     * @Column(type="boolean", length=255, unique=false, nullable=false)
     */
    private $activo = 0;
    
    /**
     * @Column(type="boolean", length=255, unique=false, nullable=false)
     */
    private $administrador = 0;
    
    /**
     * @OneToMany(targetEntity="Link", mappedBy="usuario")
     */
    private $link;
    
    /**
     * @OneToMany(targetEntity="Categoria", mappedBy="usuario")
     */
    private $categoria;
}
