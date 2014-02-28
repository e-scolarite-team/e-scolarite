<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Filiere
 *
 * @ORM\Table(name="filieres")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\FiliereRepository")
 */
class Filiere
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    protected $libelle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="effective", type="integer")
     */
    protected $effective;

     /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="Etudiant", mappedBy="filiere", cascade={"persist"})
    */

     protected $etudiants;

      /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="Module", mappedBy="filiere", cascade={"persist"})
    */

     protected $modules;

     public function __construct(){
        $this->createdAt = new \DateTime();
        $this->etudiants =  new ArrayCollection();
        $this->modules =  new ArrayCollection();
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
     * Set code
     *
     * @param string $code
     * @return Filiere
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Filiere
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Filiere
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
     * Set effective
     *
     * @param integer $effective
     * @return Filiere
     */
    public function setEffective($effective)
    {
        $this->effective = $effective;

        return $this;
    }

    /**
     * Get effective
     *
     * @return integer 
     */
    public function getEffective()
    {
        return $this->effective;
    }

    /**
    * Add etudiant
    * @param Etudiant $etudiant
    * @return Filiere
    */
    public function addEtudiant($etudiant){
        $this->etudiants[] = $etudiant;
        $etudiant->setFiliere($this);
        return $this;
    }

    /**
    * Get etudiants
    * 
    * @return array
    */
    public function getEtudiants(){
        return $this->etudiants->toArray();
    }

    /**
    * Add module
    * @param Module $module
    * @return Filiere
    */
    public function addModule($module){
        $this->modules[] = $module;
        $module->setFiliere($this);
        return $this;
    }

    /**
    * Get modules
    * 
    * @return array
    */
    public function getModules(){
        return $this->modules->toArray();
    }
}
