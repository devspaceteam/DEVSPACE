<?php

namespace ServicesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PepiniereBundle\Entity\Notification;
use SBC\NotificationsBundle\Builder\NotificationBuilder;
use SBC\NotificationsBundle\Model\NotifiableInterface;
/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation")
 * @ORM\Entity(repositoryClass="ServicesBundle\Repository\ReclamationRepository")
 */
class Reclamation implements NotifiableInterface, \JsonSerializable
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
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="userToClaim", type="string", length=255)
     */
    private $userToClaim;

    /**
     * @ORM\ManyToOne(targetEntity="PepiniereBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */

    private $User;

    /**
     * @var int
     *
     * @ORM\Column(name="state", type="integer")
     */
    private $state;

    /**
     * @var int
     *
     * @ORM\Column(name="important", type="integer")
     */
    private $important;
    /**
     * @var int
     *
     * @ORM\Column(name="trash", type="integer")
     */
    private $trash=0;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="$datetrash", type="date", nullable=true)
     */
    private $datetrash;

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
     * Set subject
     *
     * @param string $subject
     *
     * @return Reclamation
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Reclamation
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reclamation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Reclamation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set userToClaim
     *
     * @param string $userToClaim
     *
     * @return Reclamation
     */
    public function setUserToClaim($userToClaim)
    {
        $this->userToClaim = $userToClaim;

        return $this;
    }

    /**
     * Get userToClaim
     *
     * @return string
     */
    public function getUserToClaim()
    {
        return $this->userToClaim;
    }

    /**
     * Set user
     *
     * @param \PepiniereBundle\Entity\User $user
     *
     * @return Reclamation
     */
    public function setUser(\PepiniereBundle\Entity\User $user = null)
    {
        $this->User = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PepiniereBundle\Entity\User
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return Reclamation
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set important
     *
     * @param integer $important
     *
     * @return Reclamation
     */
    public function setImportant($important)
    {
        $this->important = $important;

        return $this;
    }

    /**
     * Get important
     *
     * @return integer
     */
    public function getImportant()
    {
        return $this->important;
    }

    /**
     * Set trash
     *
     * @param integer $trash
     *
     * @return Reclamation
     */
    public function setTrash($trash)
    {
        $this->trash = $trash;

        return $this;
    }

    /**
     * Get trash
     *
     * @return integer
     */
    public function getTrash()
    {
        return $this->trash;
    }

    /**
     * Set datetrash
     *
     * @param \DateTime $datetrash
     *
     * @return Reclamation
     */
    public function setDatetrash($datetrash)
    {
        $this->datetrash = $datetrash;

        return $this;
    }

    /**
     * Get datetrash
     *
     * @return \DateTime
     */
    public function getDatetrash()
    {
        return $this->datetrash;
    }

    public function getUserr()
    {
        $user=null;
        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

        }
        return $user;
    }

    public function notificationsOnCreate(NotificationBuilder $builder)
    {
        // TODO: Implement notificationsOnCreate() method.
        $notification = new Notification();
        $notification
            ->setTitle('Nouvelle Reclamation')
            ->setDescription(' A été crée')
            ->setRoute('#')
           // ->setParameters(array('id' => $this->userToClaim))
        ;
        //$notification->setIcon($this->getUserr()->getUsername());
        $notification->setUser1($this->getUser()->getUsername());
        $notification->setUser2($this->getUserToClaim());
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
