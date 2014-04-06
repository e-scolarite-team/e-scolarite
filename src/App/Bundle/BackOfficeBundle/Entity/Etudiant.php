<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Etudiant
 *
 * @ORM\Table(name="etudiants",uniqueConstraints={@ORM\UniqueConstraint(name="indx_code_apo",columns={"code_appog"})})
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\EtudiantRepository")
 */
class Etudiant extends ContainerAware  implements AdvancedUserInterface, EquatableInterface
{
    public function __toString()
    {
        return "etudiant";
    }
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=255)
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="code_appog", type="string", length=255)
     */
    protected $codeAppogee;

    /**
     * @var string
     *
     * @ORM\Column(name="cne", type="string", length=255)
     */
    protected $cne;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=255)
     */
    protected $cin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="datetime")
     */
    protected $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_naissance", type="string", length=255)
     */
    protected $villeNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_ar", type="string", length=255)
     */
    protected $nomAr;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_ar", type="string", length=255)
     */
    protected $prenomAr;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255)
     */
    protected $sexe;

    /**
     * @var integer
     *
     * @ORM\Column(name="annee_inscription", type="integer")
     */
    protected $anneeInscription;

    /**
     * @var integer
     *
     * @ORM\Column(name="annee_depart", type="integer", nullable=true)
     */
    protected $anneeDepart;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    protected $adresse;

    /**
    * @var Demande
    *
    * @ORM\OneToMany(targetEntity="Demande", mappedBy="etudiant", cascade={"persist"})
    */
    protected $demandes;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="Reclamation", mappedBy="etudiant", cascade={"persist"})
    */
    protected $reclamations;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="ResultatElp", mappedBy="etudiant", cascade={"persist"})
    */
    protected $resultats;

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
    * @var array $roles
    * @ORM\Column(name="roles", type="array", nullable=true)
    */
    protected $roles ;

    /**
    * @param ContainerInterface
    *
    */
    public function __construct(ContainerInterface $container = null){
        $this->demandes =  new ArrayCollection();
        $this->reclamations =  new ArrayCollection();
        $this->resultats =  new ArrayCollection();
        $this->last_visite_at = new \DateTime();
        $this->salt = sha1(uniqid(mt_rand(), true));
        $this->roles = array("ROLE_STUDENT");
        $this->container = $container;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Etudiant
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
    * Get username
    *
    * @return string
    */
    public function getUsername(){
        return $this->cne;
    }

    /**
     * Set codeAppogee
     *
     * @param string $codeApogee
     * @return Etudiant
     */
    public function setCodeAppogee($codeAppogee)
    {
        $this->codeAppogee = $codeAppogee;

        return $this;
    }

    /**
     * Get codeAppogee
     *
     * @return string 
     */
    public function getCodeAppogee()
    {
        return $this->codeAppogee;
    }

    /**
     * Set cne
     *
     * @param string $cne
     * @return Etudiant
     */
    public function setCne($cne)
    {
        $this->cne = $cne;

        return $this;
    }

    /**
     * Get cne
     *
     * @return string 
     */
    public function getCne()
    {
        return $this->cne;
    }

    /**
     * Set cin
     *
     * @param string $cin
     * @return Etudiant
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string 
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Etudiant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set villeNaissance
     *
     * @param string $villeNaissance
     * @return Etudiant
     */
    public function setVilleNaissance($villeNaissance)
    {
        $this->villeNaissance = $villeNaissance;

        return $this;
    }

    /**
     * Get villeNaissance
     *
     * @return string 
     */
    public function getVilleNaissance()
    {
        return $this->villeNaissance;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Etudiant
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
     * @return Etudiant
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
     * Set nomAr
     *
     * @param string $nomAr
     * @return Etudiant
     */
    public function setNomAr($nomAr)
    {
        $this->nomAr = $nomAr;

        return $this;
    }

    /**
     * Get nomAr
     *
     * @return string 
     */
    public function getNomAr()
    {
        return $this->nomAr;
    }

    /**
     * Set prenomAr
     *
     * @param string $prenomAr
     * @return Etudiant
     */
    public function setPrenomAr($prenomAr)
    {
        $this->prenomAr = $prenomAr;

        return $this;
    }

    /**
     * Get prenomAr
     *
     * @return string 
     */
    public function getPrenomAr()
    {
        return $this->prenomAr;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return Etudiant
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set anneeInscription
     *
     * @param integer $anneeInscription
     * @return Etudiant
     */
    public function setAnneeInscription($anneeInscription)
    {
        $this->anneeInscription = $anneeInscription;

        return $this;
    }

    /**
     * Get anneeInscription
     *
     * @return integer 
     */
    public function getAnneeInscription()
    {
        return $this->anneeInscription;
    }

    /**
     * Set anneeDepart
     *
     * @param integer $anneeDepart
     * @return Etudiant
     */
    public function setAnneeDepart($anneeDepart)
    {
        $this->anneeDepart = $anneeDepart;

        return $this;
    }

    /**
     * Get anneeDepart
     *
     * @return integer 
     */
    public function getAnneeDepart()
    {
        return $this->anneeDepart;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Etudiant
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
    * Add demande
    * @param Demande $demande
    * @return Etudiant
    */
    public function addDemande($demande){
        $this->demandes[] = $demande;
        $demande->setEtudiant($this);
        return $this;
    }

    /**
    * Get demandes
    * 
    * @return array
    */
    public function getDemandes(){
        return $this->demandes->toArray();
    }

    /**
    * Add reclamation
    * @param Reclamation $reclamation
    * @return Etudiant
    */
    public function addReclamation($reclamation){
        $this->reclamations[] = $reclamation;
        $reclamation->setEtudiant($this);
        return $this;
    }

    /**
    * Get reclamations
    * 
    * @return array
    */
    public function getReclamations(){
        return $this->reclamations->toArray();
    }

    /**
    * Add resultat
    * @param ResultatElp $resultat
    * @return Etudiant
    */
    public function addResultat($resultat){
        $this->resultats[] = $resultat;
        $note->setEtudiant($this);
        return $this;
    }

    /**
    * Get resultats
    * 
    * @return array
    */
    public function getResultats(){
        return $this->resultats->toArray();
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
     * Set container
     *
     * @param ContainerInterface $container
     * @return Etudiant
     */
    public function setContainer(ContainerInterface $container = NULL)
    {
        $this->container = $container;

        return $this;
    }

    /**
    * @access public 
    *
    * @param ContainerAware $container
    * 
    */
    public function preparePassword(ContainerAware $container = null){
        if($container != null) 
            $this->container = $container;

        $enFactory = $this->container->get('security.encoder_factory');
        $encoder = $enFactory->getEncoder($this);
        
        $this->setPassword($encoder->encodePassword($this->getCodeAppogee(),$this->getSalt()));
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
        return true;
      }
      
      public function isEqualTo(UserInterface $user)
      {
        if (!$user instanceof Etudiant) {
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
