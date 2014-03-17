<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Admin
 *
 * @ORM\Table(name="admins")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\AdminRepository")
 */
class Admin implements AdvancedUserInterface,EquatableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;
  
    /**
    * @var \DateTime
    *
    *
    * @ORM\Column(name="last_visite_at", type="datetime", nullable=true)
    */
    protected $last_visite_at;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="expired", type="boolean", nullable=true)
     */
    protected $expired;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="EtatDemande", mappedBy="admin", cascade={"persist"})
    */
    protected $etatDemandes;

    /**
    * @var array $roles
    * @ORM\Column(name="roles", type="array", nullable=true)
    */
    protected $roles ;

    public function __construct(){
        $this->createdAt = new \DateTime();
        $this->last_visite_at = new \DateTime();
        $this->etatDemandes = new ArrayCollection();
        $this->salt = sha1(uniqid(mt_rand(), true));
        $this->roles = array();
        $this->expired = false;
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
  * Set username
  *
  * @param string $username
  * @return Admin
  */
  public function setUsername($username){
    $this->email = $username;
    return $this;
  }

  /**
  * Get username
  *
  * @return string
  */
  public function getUsername(){
    return $this->email;
  }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Admin
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Admin
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Admin
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Admin
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set last_visite_at
     *
     * @param \DateTime $last_visite_at
     * @return Admin
     */
    public function setLastVisiteAt($last_visite_at)
    {
        $this->last_visite_at = $last_visite_at;

        return $this;
    }

    /**
     * Get last_visite_at
     *
     * @return \DateTime 
     */
    public function getLastVisiteAt()
    {
        return $this->last_visite_at;
    }


    /**
     * Set password
     *
     * @param string $password
     * @return Admin
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
     * Set salt
     *
     * @param string $salt
     * @return Admin
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set expired
     *
     * @param boolean $expired
     * @return Admin
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * Get expired
     *
     * @return boolean 
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
    * Add etatDemande
    * @param EtatDemande $etatDemande
    * @return Demande
    */
    public function addEtatDemande($etatDemande){
        $this->etatDemandes[] = $etatDemande;
        $etatDemande->setDemande($this);
        return $this;
    }

    /**
    * Get etatDemandes
    * 
    * @return array
    */
    public function getEtatDemandes(){
        return $this->etatDemandes->toArray();
    }

    /**
  * Add role
  *
  * @param Role $role
  * @return Admin
  */
  public function addRole($role){
    $this->roles[] = $role;
    return $this;
  }

  /**
  * Get roles
  *
  * @return array
  */
  public function getRoles(){
    return $this->roles;
  }

  /**
  * @access public
  *
  * @return boolean
  */
  public function equals(UserInterface $user){

    return $this->getUsername() == $user->getUsername();

  }

  /**
  * @access public
  * @see Symfony\Component\Security\Core\User.UserInterface::eraseCredentials()
  */
  public function eraseCredentials(){
    return '';
  }

  /**
  * @access public
  *
  * @return boolean
  */
  public function isAccountNonExpired()
  {
    return true;
  }

  /**
  * @access public
  *
  * @return boolean
  */
  public function isAccountNonLocked()
  {
    return true;
  }

  /**
  * @access public
  *
  * @return boolean
  */
  public function isCredentialsNonExpired()
  {
    return true;
  }

  /**
  * @access public
  *
  * @return boolean
  */
  public function isEnabled()
  {
    return !$this->expired;
  }
  
  public function isEqualTo(UserInterface $user)
  {
    if (!$user instanceof Admin) {
        return false;
    }
  
    if ($this->password !== $user->getPassword()) {
        return false;
    }
  
    if ($this->salt !== $user->getSalt()) {
        return false;
    }
  
    if ($this->getUsername() !== $user->getUsername()) {
        return false;
    }
  
    return true;
  }
}
