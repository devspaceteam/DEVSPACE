<?php

namespace VenteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison")
 * @ORM\Entity(repositoryClass="VenteBundle\Repository\LivraisonRepository")
 */
class Livraison
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
     * @ORM\ManyToOne(targetEntity="VenteBundle\Entity\Panier")
     * @ORM\JoinColumn(name="IdPanier", referencedColumnName="id")
     *
     */
    private $Panier;
    /**
     * @ORM\ManyToOne(targetEntity="ProduitBundle\Entity\Produit")
     * @ORM\JoinColumn(name="IdProduit", referencedColumnName="id")
     *
     */
    private $Produit;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateDepart", type="date")
     */
    private $dateDepart;






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
     * Set dateDepart
     *
     * @param \DateTime $dateDepart
     *
     * @return Livraison
     */
    public function setDateDepart($dateDepart)
    {
        $this->dateDepart = $dateDepart;
    
        return $this;
    }

    /**
     * Get dateDepart
     *
     * @return \DateTime
     */
    public function getDateDepart()
    {
        return $this->dateDepart;
    }







    /**
     * Set panier
     *
     * @param \VenteBundle\Entity\Panier $panier
     *
     * @return Livraison
     */
    public function setPanier(\VenteBundle\Entity\Panier $panier = null)
    {
        $this->Panier = $panier;
    
        return $this;
    }

    /**
     * Get panier
     *
     * @return \VenteBundle\Entity\Panier
     */
    public function getPanier()
    {
        return $this->Panier;
    }

    /**
     * Set produit
     *
     * @param \ProduitBundle\Entity\Produit $produit
     *
     * @return Livraison
     */
    public function setProduit(\ProduitBundle\Entity\Produit $produit = null)
    {
        $this->Produit = $produit;
    
        return $this;
    }

    /**
     * Get produit
     *
     * @return \ProduitBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->Produit;
    }
}
