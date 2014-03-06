<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Validator\Constraints as Validator;

/**
 * TypeDemande
 *
 * @ORM\Table(name="type_demandes")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\TypeDemandeRepository")
 */
class TypeDemande
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     * @Validator\NotBlank(message="errors.typedemande.code")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     * @Validator\NotBlank(message="errors.typedemande.libelle")
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_autorise", type="integer", nullable=true)
     * @Validator\NotBlank(message="errors.typedemande.maxauto")
     * @Validator\Type(type="int", message="errors.typedemande.maxauto")
     */
    private $maxAutorise;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="Demande", mappedBy="typeDemande", cascade={"persist"})
    */
    protected $demandes;


    public function __construct(){
        $this->demandes =  new ArrayCollection();
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
     * @return TypeDemande
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
     * @return TypeDemande
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
     * @return TypeDemande
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
    * Add demande
    * @param Demande $demande
    * @return TypeDemande
    */
    public function addDemande($demande){
        $this->demandes[] = $demande;
        $demande->setTypeDemande($this);
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

    public function count(){
        return $this->demandes->count();
    }
}
