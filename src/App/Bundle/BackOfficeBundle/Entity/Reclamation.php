<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamations")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\ReclamationRepository")
 */
class Reclamation
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
     * @ORM\Column(name="objet", type="string", length=255, nullable=true)
     */
    protected $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="text", nullable=true)
     */
    protected $reponse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="consulted_at", type="datetime", nullable=true)
     */
    protected $consultedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status;

    /**
     * @var Etudiant
     *
     * @ORM\ManyToOne(targetEntity="Etudiant", inversedBy="reclamations")
     * @ORM\JoinColumn(name="etudiant_id", referencedColumnName="id")
     */
    protected $etudiant;

    /**
     * @var TypeReclamation
     *
     * @ORM\ManyToOne(targetEntity="TypeReclamation", inversedBy="reclamations")
     * @ORM\JoinColumn(name="type_reclamation_id", referencedColumnName="id")
     */
    protected $typeReclamation;


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
     * Set objet
     *
     * @param string $objet
     * @return Reclamation
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string 
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Reclamation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set reponse
     *
     * @param string $reponse
     * @return Reclamation
     */
    public function setreponse($reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return string 
     */
    public function getreponse()
    {
        return $this->reponse;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Reclamation
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
     * Set consultedAt
     *
     * @param \DateTime $consultedAt
     * @return Reclamation
     */
    public function setConsultedAt($consultedAt)
    {
        $this->consultedAt = $consultedAt;

        return $this;
    }

    /**
     * Get consultedAt
     *
     * @return \DateTime 
     */
    public function getConsultedAt()
    {
        return $this->consultedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Reclamation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set etudiant
     *
     * @param Etudiant $etudiant
     * @return Reclamation
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
     * Set typeReclamation
     *
     * @param TypeReclamation $typeReclamation
     * @return Reclamation
     */
    public function setTypeReclamation($typeReclamation)
    {
        $this->typeReclamation = $typeReclamation;

        return $this;
    }

    /**
     * Get TypeReclamation
     *
     * @return TypeReclamation 
     */
    public function getTypeReclamation()
    {
        return $this->typeReclamation;
    }
    
}
