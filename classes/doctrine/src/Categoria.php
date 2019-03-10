<?php

namespace izv\data;

/**
 * @Entity @Table(name="categoria")
 * 
 */
class Categoria {
    
    /**
     * @Id
     * @Column(type="bigint") @GeneratedValue
     */
    private $id; 
    
    /**
     * @Column(type="string", unique=true, length=255, nullable=false)
     */
    private $nombre;
    
    /**
     * @ManyToOne(targetEntity="Usuario", inversedBy="categorias")
     * @JoinColumn(name="idAutor", referencedColumnName="id")
     */
    private $usuario;
    
    /**
     * @OneToMany(targetEntity="Link", mappedBy="categoria")
     */
    private $link;
    
}
