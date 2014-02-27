<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="notes")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\NoteRepository")
 */
class Note
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float")
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="remarques", type="string", length=255, nullable=true)
     */
    private $remarques;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @var integer
     *
     * @ORM\Column(name="annee", type="integer", nullable=true)
     */
    private $annee;

    /**
     * @var Etudiant
     *
     * @ORM\ManyToOne(targetEntity="Etudiant", inversedBy="notes")
     * @ORM\JoinColumn(name="etudiant_id", referencedColumnName="id")
     */
    protected $etudiant;

    /**
     * @var Element
     *
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="elements")
     * @ORM\JoinColumn(name="element_id", referencedColumnName="id")
     */
    protected $element;


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
     * Set note
     *
     * @param float $note
     * @return Note
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
     * Set remarques
     *
     * @param string $remarques
     * @return Note
     */
    public function setRemarques($remarques)
    {
        $this->remarques = $remarques;

        return $this;
    }

    /**
     * Get remarques
     *
     * @return string 
     */
    public function getRemarques()
    {
        return $this->remarques;
    }

    /**
     * Set etat
     *
     * @param string $etat
     * @return Note
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
     * Set annee
     *
     * @param integer $annee
     * @return Note
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
     * Set etudiant
     *
     * @param Etudiant $etudiant
     * @return Note
     */

    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return etudiant 
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set element
     *
     * @param Element $element
     * @return Note
     */

    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return Element 
     */
    public function getElement()
    {
        return $this->element;
    }


}
