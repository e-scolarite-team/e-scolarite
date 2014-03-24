<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtatDemande
 *
 * @ORM\Table(name="etat_demandes")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\EtatDemandeRepository")
 */
class EtatDemande
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
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    protected $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="justification", type="string", length=255, nullable=true)
     */
    protected $justification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var Demande
     *
     * @ORM\ManyToOne(targetEntity="Demande", inversedBy="etatDemandes")
     * @ORM\JoinColumn(name="demande_id", referencedColumnName="id")
     */
    protected $demande;

    /**
     * @var Admin
     *
     * @ORM\ManyToOne(targetEntity="Admin", inversedBy="etatDemandes")
     * @ORM\JoinColumn(name="admin_id", referencedColumnName="id")
     */
    protected $admin;

    public function __construct(){
        $this->createdAt = new \DateTime();
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
     * Set etat
     *
     * @param string $etat
     * @return EtatDemande
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set justification
     *
     * @param string $justification
     * @return EtatDemande
     */
    public function setJustification($justification)
    {
        $this->justification = $justification;

        return $this;
    }

    /**
     * Get justification
     *
     * @return string 
     */
    public function getJustification()
    {
        return $this->justification;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return EtatDemande
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
     * Set demande
     *
     * @param Demande $demande
     * @return EtatDemande
     */
    public function setDemande($demande)
    {
        $this->demande = $demande;

        return $this;
    }

    /**
     * Get demande
     *
     * @return EtatDemande 
     */
    public function getDemande()
    {
        return $this->demande;
    }

    /**
     * Set admin
     *
     * @param Admin $admin
     * @return EtatDemande
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return Demande 
     */
    public function getAdmin()
    {
        return $this->admin;
    }
    
}
