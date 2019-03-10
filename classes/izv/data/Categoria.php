<?php

namespace izv\data;

/**
 * @Entity @Table(name="categoria")
 * 
 */
class Categoria {
    
    use \izv\common\Common;
    
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->link = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Categoria
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
     * Set usuario
     *
     * @param \izv\data\Usuario $usuario
     *
     * @return Categoria
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
     * Add link
     *
     * @param \izv\data\Link $link
     *
     * @return Categoria
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
}

