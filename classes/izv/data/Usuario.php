<?php

namespace izv\data;

/**
 * @Entity @Table(name="usuario")
 * 
 */
class Usuario {
    
    use \izv\common\Common;
    
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->link = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoria = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set correo
     *
     * @param string $correo
     *
     * @return Usuario
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Usuario
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set clave
     *
     * @param string $clave
     *
     * @return Usuario
     */
    public function setClave($clave)
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * Get clave
     *
     * @return string
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Usuario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set administrador
     *
     * @param boolean $administrador
     *
     * @return Usuario
     */
    public function setAdministrador($administrador)
    {
        $this->administrador = $administrador;

        return $this;
    }

    /**
     * Get administrador
     *
     * @return boolean
     */
    public function getAdministrador()
    {
        return $this->administrador;
    }

    /**
     * Add link
     *
     * @param \izv\data\Link $link
     *
     * @return Usuario
     */
    public function addLink(\izv\data\Link $link)
    {
        $this->link[] = $link;

        return $this;
    }

    /**
     * Remove link
     *
     * @param \izv\data\Link $link
     */
    public function removeLink(\izv\data\Link $link)
    {
        $this->link->removeElement($link);
    }

    /**
     * Get link
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Add categorium
     *
     * @param \izv\data\Categoria $categorium
     *
     * @return Usuario
     */
    public function addCategorium(\izv\data\Categoria $categorium)
    {
        $this->categoria[] = $categorium;

        return $this;
    }

    /**
     * Remove categorium
     *
     * @param \izv\data\Categoria $categorium
     */
    public function removeCategorium(\izv\data\Categoria $categorium)
    {
        $this->categoria->removeElement($categorium);
    }

    /**
     * Get categoria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
}

