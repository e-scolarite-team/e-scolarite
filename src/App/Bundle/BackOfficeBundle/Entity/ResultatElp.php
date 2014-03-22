<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResultatElp
 *
 * @ORM\Table(name="results_elp")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\ResultatElpRepository")
 */
class ResultatElp
{
    
    /**
     * @var ElementPedagogi
     * @ORM\Id
     * 
     * @ORM\ManyToOne(targetEntity="ElementPedagogi", inversedBy="resultats")
     * @ORM\JoinColumn(name="elp_code", referencedColumnName="code")
     */
    protected $element;

    /**
     * @var Etudiant
     * @ORM\Id
     * 
     * @ORM\ManyToOne(targetEntity="Etudiant", inversedBy="resultats")
     * @ORM\JoinColumn(name="etudiant_id", referencedColumnName="id")
     *
     */
    protected $etudiant;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="annee", type="integer")
     */
    protected $annee;

    /**
     * @var integer
     * @ORM\Id
     * 
     * @ORM\Column(name="session", type="integer")
     */
    protected $session;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="admissibilite", type="integer")
     */
    protected $admissibilite;

    /**
     * @var float
     * 
     * 
     * @ORM\Column(name="note", type="float")
     */
    protected $note;

    /**
     * @var string
     * 
     * 
     * @ORM\Column(name="status", type="string", length=255)
     */
    protected $status;

    /*public function __construct($etudiant,$element,$annee,$session,$admissibilite){
        $this->element = $element;
        $this->etudiant = $etudiant;
        $this->annee = $annee;
        $this->admissibilite = $admissibilite;
        $this->session = $session;
    }*/

    public function __construct(){
        
    }


    /**
     * Set element
     *
     * @param ElementPedagogi $element
     * @return ResultatElp
     */
    public function setElement(ElementPedagogi $element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return ElementPedagogi 
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set etudiant
     *
     * @param Etudiant $etudiant
     * @return ResultatElp
     */
    public function setEtudiant(Etudiant $etudiant)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return string 
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     * @return ResultatElp
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set session
     *
     * @param integer $session
     * @return ResultatElp
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return integer 
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set admissibilite
     *
     * @param integer $admissibilite
     * @return ResultatElp
     */
    public function setAdmissibilite($admissibilite)
    {
        $this->admissibilite = $admissibilite;

        return $this;
    }

    /**
     * Get admissibilite
     *
     * @return integer 
     */
    public function getAdmissibilite()
    {
        return $this->admissibilite;
    }

    /**
     * Set note
     *
     * @param float $note
     * @return ResultatElp
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return float 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return ResultatElp
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
