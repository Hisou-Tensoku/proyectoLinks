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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->links = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set mail
     *
     * @param string $mail
     *
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return User
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
     * Set admin
     *
     * @param boolean $admin
     *
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set dateup
     *
     * @param \DateTime $dateup
     *
     * @return User
     */
    public function setDateup($dateup)
    {
        $this->dateup = $dateup;

        return $this;
    }

    /**
     * Get dateup
     *
     * @return \DateTime
     */
    public function getDateup()
    {
        return $this->dateup;
    }

    /**
     * Add link
     *
     * @param \izv\data\Link $link
     *
     * @return User
     */
    public function addLink(\izv\data\Link $link)
    {
        $this->links[] = $link;

        return $this;
    }

    /**
     * Remove link
     *
     * @param \izv\data\Link $link
     */
    public function removeLink(\izv\data\Link $link)
    {
        $this->links->removeElement($link);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Add category
     *
     * @param \izv\data\Category $category
     *
     * @return User
     */
    public function addCategory(\izv\data\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \izv\data\Category $category
     */
    public function removeCategory(\izv\data\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }
}

