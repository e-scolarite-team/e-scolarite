<?php

namespace App\Bundle\BackOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeReclamation
 *
 * @ORM\Table()
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_autorise", type="integer")
     */
    private $maxAutorise;


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
}
