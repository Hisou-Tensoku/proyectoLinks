<?php

namespace izv\data;

/**
 * @Entity @Table(name="link")
 * 
 */
class Link {
    
    /**
     * @Id
     * @Column(type="bigint") @GeneratedValue
     */
    private $id; 
    
    /**
     * @Column(type="string", length=255, nullable=false)
     */
    private $href;
    
    /**
     * @Column(type="text", length=255)
     */
    private $descripcion;
    
    /**
     * @ManyToOne(targetEntity="Usuario", inversedBy="links")
     * @JoinColumn(name="idUsuario", referencedColumnName="id")
     */
    private $usuario;
    
    /**
     * @ManyToOne(targetEntity="Categoria", inversedBy="links")
     * @JoinColumn(name="idCategoria", referencedColumnName="id")
     */
    private $categoria;

}
