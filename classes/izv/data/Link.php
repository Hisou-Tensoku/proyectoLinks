<?php

namespace izv\data;

/**
 * @Entity @Table(name="link")
 * 
 */
class Link {
    
    use \izv\common\Common;
    
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


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set href
     *
     * @param string $href
     *
     * @return Link
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Get href
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Link
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set usuario
     *
     * @param \izv\data\Usuario $usuario
     *
     * @return Link
     */
    public function setUsuario(\izv\data\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \izv\data\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set categoria
     *
     * @param \izv\data\Categoria $categoria
     *
     * @return Link
     */
    public function setCategoria(\izv\data\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \izv\data\Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
}

