<?php

namespace izv\data;

/**
 * @Entity @Table(name="category")
 * 
 */
class Category {
    
    /**
     * @Id
     * @Column(type="bigint") @GeneratedValue
     */
    private $id; 
    
    /**
     * @Column(type="string", unique=true, length=255, nullable=false)
     */
    private $name;
    
    /**
     * @ManyToOne(targetEntity="User", inversedBy="categories")
     * @JoinColumn(name="author_id", referencedColumnName="id", unique=true)
     */
    private $author;
    
    /**
     * @Column(type="boolean", nullable=false)
     */
    private $active = true;


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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Category
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set author
     *
     * @param \izv\data\User $author
     *
     * @return Category
     */
    public function setAuthor(\izv\data\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \izv\data\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}

