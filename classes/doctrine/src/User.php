<?php

namespace izv\data;

/**
 * @Entity @Table(name="user")
 * 
 */
class User {
    
    /**
     * @Id
     * @Column(type="bigint") @GeneratedValue
     */
    private $id; 
    
    /**
     * @Column(type="string", length=255, unique=true, nullable=false)
     */
    private $mail;
    
    /**
     * @Column(type="string", length=255, unique=true, nullable=false)
     */
    private $nickname;
    
    /**
     * @Column(type="string", length=255, unique=false, nullable=true)
     */
    private $name;
    
    /**
     * @Column(type="string", length=255, unique=false)
     */
    private $lastname;
    
    /**
     * @Column(type="string", length=255)
     */
    private $address;
    
    /**
     * @Column(type="string", length=255, unique=true, nullable=false)
     */
    private $password;
    
    /**
     * @Column(type="boolean", length=255, unique=false, nullable=false)
     */
    private $active = 0;
    
    /**
     * @Column(type="boolean", length=255, unique=false, nullable=false)
     */
    private $admin = 0;
    
    /**
     * @Column(type="datetime", unique=false, nullable=false, columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $dateup;
    
    /**
     * @OneToMany(targetEntity="Link", mappedBy="user")
     */
    private $links;
    
    /**
     * @OneToMany(targetEntity="Category", mappedBy="user")
     */
    private $category;
}
