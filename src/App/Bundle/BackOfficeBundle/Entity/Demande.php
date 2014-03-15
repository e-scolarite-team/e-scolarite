<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Demande
 *
 * @ORM\Table(name="demandes")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\DemandeRepository")
 */
class Demande
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="remarque", type="string", length=255, nullable=true)
     */
    protected $remarque;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reponce", type="date", nullable=true)
     */
    protected $dateReponce;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notified", type="boolean", nullable=true)
     */
    protected $notified;

    /**
     * @var Etudiant
     *
     * @ORM\ManyToOne(targetEntity="Etudiant", inversedBy="demandes")
     * @ORM\JoinColumn(name="etudiant_id", referencedColumnName="id")
     */
    protected $etudiant;

    /**
     * @var TypeDemande
     *
     * @ORM\ManyToOne(targetEntity="TypeDemande", inversedBy="demandes")
     * @ORM\JoinColumn(name="type_demande_id", referencedColumnName="id")
     */
    protected $typeDemande;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="EtatDemande", mappedBy="demande", cascade={"persist"})
    */
    protected $etatDemandes;

    public function __construct(){
        $this->createdAt = new \DateTime();
        $this->etatDemandes = new ArrayCollection();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Demande
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Demande
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     * @return Demande
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string 
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Demande
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateReponce
     *
     * @param \DateTime $dateReponce
     * @return Demande
     */
    public function setDateReponce($dateReponce)
    {
        $this->dateReponce = $dateReponce;

        return $this;
    }

    /**
     * Get dateReponce
     *
     * @return \DateTime 
     */
    public function getDateReponce()
    {
        return $this->dateReponce;
    }

    /**
     * Set notified
     *
     * @param boolean $notified
     * @return Demande
     */
    public function setNotified($notified)
    {
        $this->notified = $notified;

        return $this;
    }

    /**
     * Get notified
     *
     * @return boolean 
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * Set etudiant
     *
     * @param Etudiant $etudiant
     * @return Demande
     */
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return Etudiant 
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }


    /**
     * Set typeDemande
     *
     * @param TypeDemande $typeDemande
     * @return Demande
     */
    public function setTypeDemande($typeDemande)
    {
        $this->typeDemande = $typeDemande;

        return $this;
    }

    /**
     * Get typeDemande
     *
     * @return TypeDemande 
     */
    public function getTypeDemande()
    {
        return $this->typeDemande;
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
}
