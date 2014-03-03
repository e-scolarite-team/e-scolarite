<?php

namespace App\Bundle\BackOfficeBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="modules")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\ModuleRepository")
 */
class Module
{
    /**
     * @var string
     * @ORM\Id
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
     * @var Filiere
     *
     * @ORM\ManyToOne(targetEntity="Filiere", inversedBy="filieres")
     * @ORM\JoinColumn(name="filiere_code", referencedColumnName="code")
     */
    protected $filiere;

    /**
     * @var Semestre
     *
     * @ORM\ManyToOne(targetEntity="Semestre", inversedBy="semestres")
     * @ORM\JoinColumn(name="semestre_code", referencedColumnName="code")
     */
    protected $semestre;

    /**
    * @var Element
    *
    * @ORM\OneToMany(targetEntity="Element", mappedBy="module", cascade={"persist"})
    */
    protected $elements;

    public function __construct(){
        $this->elements =  new ArrayCollection();        
    }


    

    /**
     * Set code
     *
     * @param string $code
     * @return Module
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
     * @return Module
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
     * Set filiere
     *
     * @param Filiere $filiere
     * @return Module
     */
    public function setFiliere($filiere)
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * Get filiere
     *
     * @return Filiere 
     */
    public function getFiliere()
    {
        return $this->filiere;
    }

    

    /**
     * Get semestre
     *
     * @return Semestre 
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

     /**
    * Add Element
    * @param element $element
    * @return Module
    */
    public function addElement($element){
        $this->elements[] = $element;
        $element->setModule($this);
        return $this;
    }

    /**
    * Get elements
    * 
    * @return array
    */
    public function getElements(){
        return $this->elements->toArray();
    }
}
