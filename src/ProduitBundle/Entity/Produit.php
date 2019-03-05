<?php

namespace ProduitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="ProduitBundle\Repository\ProduitRepository")
 * @Vich\Uploadable
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Categorie" )
     * @ORM\JoinColumn(referencedColumnName="id" , onDelete="CASCADE" )
     */
    private $Categorie;

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->Categorie;
    }

    /**
     * @param mixed $Categorie
     */
    public function setCategorie($Categorie)
    {
        $this->Categorie = $Categorie;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param mixed $User
     */
    public function setUser($User)
    {
        $this->User = $User;
    }

    /**
     * @ORM\ManyToOne(targetEntity="PepiniereBundle\Entity\User" )
     * @ORM\JoinColumn(referencedColumnName="id" )
     */

    private $User;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;
    /**
     * @Assert\Image()(
     *     maxSize="500K",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"},
     *     maxSizeMessage="Fichier trop gros"
     * )
     * @Vich\UploadableField(mapping="devis", fileNameProperty="devisName1")
     *
     * @var File $devisFile1
     */
    private $devisFile1;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $devisName1;

    /**
     * @Vich\UploadableField(mapping="devis", fileNameProperty="devisName2")
     *
     * @var File
     */
    private $devisFile2;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $devisName2;

    /**
     * @Vich\UploadableField(mapping="devis", fileNameProperty="devisName3")
     *
     * @var File
     */
    private $devisFile3;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $devisName3;












    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Produit
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
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
     * Set prix
     *
     * @param float $prix
     *
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }


    /**
     * Set updatedAt
     *
     * @param string $updatedAt
     *
     * @return Produit
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $devis
     *
     * @return Devis
     */
    public function setDevisFile1(File $devis = null)
    {
        $this->devisFile1 = $devis;

        if ($devis)
            $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return File|null
     */
    public function getDevisFile1()
    {
        return $this->devisFile1;
    }

    /**
     * @param string $devisName1
     *
     * @return Devis
     */
    public function setDevisName1($devisName1)
    {
        $this->devisName1 = $devisName1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDevisName1()
    {
        return $this->devisName1;
    }



    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $devis
     *
     * @return Devis
     */
    public function setDevisFile2(File $devis = null)
    {
        $this->devisFile2 = $devis;

        if ($devis)
            $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return File|null
     */
    public function getDevisFile2()
    {
        return $this->devisFile2;
    }

    /**
     * @param string $devisName2
     *
     * @return Devis
     */
    public function setDevisName2($devisName2)
    {
        $this->devisName2 = $devisName2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDevisName2()
    {
        return $this->devisName2;
    }



    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $devis
     *
     * @return Devis
     */
    public function setDevisFile3(File $devis = null)
    {
        $this->devisFile3 = $devis;

        if ($devis)
            $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    /**
     * @return File|null
     */
    public function getDevisFile3()
    {
        return $this->devisFile3;
    }

    /**
     * @param string $devisName3
     *
     * @return Devis
     */
    public function setDevisName3($devisName3)
    {
        $this->devisName3 = $devisName3;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDevisName3()
    {
        return $this->devisName3;
    }

    /**
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }


}

