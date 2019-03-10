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
    
}
