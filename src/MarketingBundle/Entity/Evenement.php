<?php

namespace MarketingBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="MarketingBundle\Repository\EvenementRepository")
 */
class Evenement
{


    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="nom_evenement", type="string", length=255)
     */
    private $nomEvenement;

    /**
     * @Assert\Type("DateTime")
     *
     * @ORM\Column(name="date_debut", type="date")
     * @var string A "Y-m-d" formatted value
     * @Assert\Type("DateTime")
     * @Assert\GreaterThanOrEqual("today")

     */
    private $dateDebut;

    /**
     * @Assert\Type("DateTime")
     *
     * @ORM\Column(name="date_fin", type="date")
     *

     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     *  @Assert\Length(
          min = 4,
           max = 100,
          minMessage = "Your first name must be at least {{ limit }} characters long",
         maxMessage = "Your first name cannot be longer than {{ limit }} characters")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="nbr_cado", type="float")
     *
     */
    private $nbrcado;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomEvenement
     *
     * @param string $nomEvenement
     *
     * @return Evenement
     */
    public function setNomEvenement($nomEvenement)
    {
        $this->nomEvenement = $nomEvenement;

        return $this;
    }

    /**
     * Get nomEvenement
     *
     * @return string
     */
    public function getNomEvenement()
    {
        return $this->nomEvenement;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Evenement
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Evenement
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
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
     * Set pourcentage
     *
     * @param float $pourcentage
     *
     * @return Promotion
     */
    public function setnbrcado($nbrcado)
    {
        $this->nbrcado = $nbrcado;

        return $this;
    }

    /**
     * Get nbrcado
     *
     * @return float
     */
    public function getnbrcado()
    {
        return $this->nbrcado;
    }









}

