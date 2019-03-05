<?php

namespace PepiniereBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SBC\NotificationsBundle\Model\BaseNotification;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="PepiniereBundle\Repository\NotificationRepository")
 */
class Notification extends BaseNotification implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="user1", type="string", length=255, nullable=true)
     */
    private $user1;

    /**
     * @return string
     */
    public function getUser1(): string
    {
        return $this->user1;
    }

    /**
     * @param string $user1
     */
    public function setUser1(string $user1)
    {
        $this->user1 = $user1;
    }

    /**
     * @return string
     */
    public function getUser2(): string
    {
        return $this->user2;
    }

    /**
     * @param string $user2
     */
    public function setUser2(string $user2)
    {
        $this->user2 = $user2;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="user2", type="string", length=255, nullable=true)
     */
    private $user2;

    public function __construct()
    {
        parent::__construct();
    }

    function jsonSerialize()
    {
        return get_object_vars($this);
    }


}
