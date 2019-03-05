<?php

namespace MarketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PepiniereBundle\Entity\Notification;
use SBC\NotificationsBundle\Builder\NotificationBuilder;
use SBC\NotificationsBundle\Model\NotifiableInterface;
/**
 * Promotion
 *
 * @ORM\Table(name="promotion")
 * @ORM\Entity(repositoryClass="MarketingBundle\Repository\PromotionRepository")
 */
class Promotion implements NotifiableInterface, \JsonSerializable
{


    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="nom_promotion", type="string", length=255)
     * @Assert\Length(min=5),
     *     message = "Choose a valid genre."
     */
    private $nomPromotion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date")
     * @Assert\GreaterThanOrEqual("today")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date")
     */
    private $dateFin;

    /**
     * @var float
     *
     * @ORM\Column(name="pourcentage", type="float")
     * @Assert\LessThan(value = 100)
     */
    private $pourcentage;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_initiale", type="float")
     *
     */
    private $prixInitiale;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_promo", type="float")
     */
    private $prixPromo;


    /**
     * @ORM\ManyToOne(targetEntity="ProduitBundle\Entity\Produit")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $produit;

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
     * Set nomPromotion
     *
     * @param string $nomPromotion
     *
     * @return Promotion
     */
    public function setNomPromotion($nomPromotion)
    {
        $this->nomPromotion = $nomPromotion;

        return $this;
    }

    /**
     * Get nomPromotion
     *
     * @return string
     */
    public function getNomPromotion()
    {
        return $this->nomPromotion;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Promotion
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
     * @return Promotion
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
     * Set pourcentage
     *
     * @param float $pourcentage
     *
     * @return Promotion
     */
    public function setPourcentage($pourcentage)
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    /**
     * Get pourcentage
     *
     * @return float
     */
    public function getPourcentage()
    {
        return $this->pourcentage;
    }

    /**
     * Set prixInitiale
     *
     * @param float $prixInitiale
     *
     * @return Promotion
     */
    public function setPrixInitiale($prixInitiale)
    {
        $this->prixInitiale = $prixInitiale;

        return $this;
    }

    /**
     * Get prixInitiale
     *
     * @return float
     */
    public function getPrixInitiale()
    {
        return $this->prixInitiale;
    }

    /**
     * Set prixPromo
     *
     * @param float $prixPromo
     *
     * @return Promotion
     */
    public function setPrixPromo($prixPromo)
    {
        $this->prixPromo = $prixPromo;

        return $this;
    }

    /**
     * Get prixPromo
     *
     * @return float
     */
    public function getPrixPromo()
    {
        return $this->prixPromo;
    }

    /**
     * Set produit
     *
     * @param \ProduitBundle\Entity\Produit $produit
     *
     * @return Promotion
     */
    public function setProduit(\ProduitBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \ProduitBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }
    public function notificationsOnCreate(NotificationBuilder $builder)
    {
        // TODO: Implement notificationsOnCreate() method.
        $notification = new Notification();
        $notification
            ->setTitle('Nouvelle Promotion')
            ->setDescription(' A été effectué')
            ->setRoute('#')
            // ->setParameters(array('id' => $this->userToClaim))
        ;
        //$notification->setIcon($this->getUserr()->getUsername());
      //  $notification->setUser1($this->getUser()->getUsername());
      //  $notification->setUser2($this->getUserToClaim());
        $builder->addNotification($notification);

        return $builder;
    }

    public function notificationsOnUpdate(NotificationBuilder $builder)
    {
        return $builder;
    }

    public function notificationsOnDelete(NotificationBuilder $builder)
    {
        return $builder;
    }

    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
