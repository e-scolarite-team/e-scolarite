<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Validator\Constraints as Validator;

/**
 * TypeReclamation
 *
 * @ORM\Table(name="type_reclamations")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\TypeReclamationRepository")
 */
class TypeReclamation
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
     * @Validator\NotBlank(message="errors.typereclamation.code")
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     * @Validator\NotBlank(message="errors.typereclamation.libelle")
     */
    protected $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_autorise", type="integer", nullable=true)
     * @Validator\NotBlank(message="errors.typereclamation.maxauto")
     * @Validator\Type(type="int", message="errors.typereclamation.maxautoint")
     */
    protected $maxAutorise;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="Reclamation", mappedBy="typeReclamation", cascade={"persist"})
    */
    protected $reclamations;


    public function __construct(){
        $this->reclamations =  new ArrayCollection();
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
     * @return TypeReclamation
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
     * @return TypeReclamation
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
     * Set maxAutorise
     *
     * @param integer $maxAutorise
     * @return TypeReclamation
     */
    public function setMaxAutorise($maxAutorise)
    {
        $this->maxAutorise = $maxAutorise;

        return $this;
    }

    /**
     * Get maxAutorise
     *
     * @return integer 
     */
    public function getMaxAutorise()
    {
        return $this->maxAutorise;
    }

    /**
    * Add reclamation
    * @param Demande $reclamation
    * @return TypeReclamation
    */
    public function addReclamation($reclamation){
        $this->reclamations[] = $reclamation;
        $reclamation->setTypeReclamation($this);
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

    public function count(){
        return $this->reclamations->count();
    }
}
