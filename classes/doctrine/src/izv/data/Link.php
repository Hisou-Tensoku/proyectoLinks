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
     * @Column(type="string", length=255, nullable=false, unique=true)
     */
    private $href;
    
    /**
     * @Column(type="text", length=255)
     */
    private $comment;
    
    /**
     * @ManyToOne(targetEntity="User", inversedBy="links")
     * @JoinColumn(name="idUser", referencedColumnName="id")
     */
    private $author;
    
    /**
     * @ManyToOne(targetEntity="Category", inversedBy="links")
     * @JoinColumn(name="idCategory", referencedColumnName="id")
     */
    private $category;
    
    /**
     * @Column(type="boolean", nullable=false)
     */
    private $visible = true;


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
     * Set comment
     *
     * @param string $comment
     *
     * @return Link
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Link
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set author
     *
     * @param \izv\data\User $author
     *
     * @return Link
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

    /**
     * Set category
     *
     * @param \izv\data\Category $category
     *
     * @return Link
     */
    public function setCategory(\izv\data\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \izv\data\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}

