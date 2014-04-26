<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * ElementPedagogi
 *
 * @ORM\Table(name="element_pedagogi")
 * @ORM\Entity(repositoryClass="App\Bundle\BackOfficeBundle\Entity\ElementPedagogiRepository")
 */
class ElementPedagogi
{
    

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="nature_elp", type="string", length=255)
     */
    protected $nature;

    /**
     * @var string
     *
     * @ORM\Column(name="lib_elp", type="string", length=255)
     */
    protected $lib;

    /**
     * @var string
     *
     * @ORM\Column(name="lic_elp", type="string", length=255)
     */
    protected $lic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="date", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_elp", type="string", length=255)
     */
    protected $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="lib_elp_ar", type="string", length=255, nullable=true)
     */
    protected $libAr;

    /**
     * @var string
     *
     * @ORM\Column(name="lic_elp_ar", type="string", length=255, nullable=true)
     */
    protected $licAr;

    /**
     * @var integer
     *
     * @ORM\Column(name="elp_order", type="integer", nullable=true)
     */
    protected $order;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="ElementPedagogi", mappedBy="parent", cascade={"persist"})
     */
    protected $elements;

    /**
     * @var ElementPedagogi
     * 
     * @ORM\ManyToOne(targetEntity="ElementPedagogi", inversedBy="elements")
     * @ORM\JoinColumn(name="parent_code", referencedColumnName="code")
     */
    protected $parent;

    /**
    * @var ArrayCollection
    *
    * @ORM\OneToMany(targetEntity="ResultatElp", mappedBy="element", cascade={"persist"})
    */
    protected $resultats;

    public function __construct() {
        $this->elements = new ArrayCollection();
        $this->resultats = new ArrayCollection();
    }


    /**
     * Set code
     *
     * @param string $codeElp
     * @return ElementPedagogi
     */
    public function setCode($codeElp)
    {
        $this->code = $codeElp;

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
     * Set nature
     *
     * @param string $natureElp
     * @return ElementPedagogi
     */
    public function setNature($natureElp)
    {
        $this->nature = $natureElp;

        return $this;
    }

    /**
     * Get nature
     *
     * @return string 
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set lib
     *
     * @param string $libElp
     * @return ElementPedagogi
     */
    public function setLib($libElp)
    {
        $this->lib = $libElp;

        return $this;
    }

    /**
     * Get lib
     *
     * @return string 
     */
    public function getLib()
    {
        return $this->lib;
    }

    /**
     * Set lic
     *
     * @param string $licElp
     * @return ElementPedagogi
     */
    public function setLic($licElp)
    {
        $this->lic = $licElp;

        return $this;
    }

    /**
     * Get lic
     *
     * @return string 
     */
    public function getLic()
    {
        return $this->lic;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ElementPedagogi
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
     * @return ElementPedagogi
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
     * Set etat
     *
     * @param string $etatElp
     * @return ElementPedagogi
     */
    public function setEtat($etatElp)
    {
        $this->etat = $etatElp;

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
     * Set libAr
     *
     * @param string $libElpAr
     * @return ElementPedagogi
     */
    public function setLibAr($libElpAr)
    {
        $this->libAr = $libElpAr;

        return $this;
    }

    /**
     * Get libAr
     *
     * @return string 
     */
    public function getLibAr()
    {
        return $this->libElpAr;
    }

    /**
     * Set licAr
     *
     * @param string $licElpAr
     * @return ElementPedagogi
     */
    public function setLicAr($licElpAr)
    {
        $this->licAr = $licElpAr;

        return $this;
    }

    /**
     * Get licAr
     *
     * @return string 
     */
    public function getLicAr()
    {
        return $this->licAr;
    }

    /**
     * Set order
     *
     * @param integer $elpOrder
     * @return ElementPedagogi
     */
    public function setOrder($elpOrder)
    {
        $this->order = $elpOrder;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->elpOrder;
    }
    
    /**
     * Set parent
     *
     * @param ElementPedagogi $parent
     * @return ElementPedagogi
     */
    public function setParent(ElementPedagogi $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return ElementPedagogi 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
    * Add element
    * @param ElementPedagogi $ele
    * @return ElementPedagogi
    */
    public function addElement(ElementPedagogi $ele){
        $this->elements[] = $ele;
        $ele->setParent($this);
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

    /**
    * Add resultat
    * @param ResultatElp $resultat
    * @return ElementPedagogi
    */
    public function addResultat($resultat){
        $this->resultats[] = $resultat;
        $resultat->setElement($this);
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

}
