<?php

namespace PepiniereBundle\Repository;

class NotificationRepository extends \Doctrine\ORM\EntityRepository
{

    public function findnotseen()
    {
        return $this->getEntityManager()
            ->createQuery("select m from PepiniereBundle:Notification m where m.seen = 0    ")
            ->getResult();
    }

    public function searchAll()
    {
        return $this->getEntityManager()
            ->createQuery("select m from PepiniereBundle:Notification m   ORDER BY  m.id DESC ")
            ->getResult();
    }
}