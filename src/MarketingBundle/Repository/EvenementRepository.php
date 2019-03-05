<?php

namespace MarketingBundle\Repository;

/**
 * EvenementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EvenementRepository extends \Doctrine\ORM\EntityRepository
{
    public function computeDQL($date){
        $qb=$this->getEntityManager()
            ->createQuery("DELETE FROM MarketingBundle:Evenement e  WHERE e.dateFin < :date")
            ->setParameter('date',$date);
        return $qb->getResult();
    }
    public function myfindall($nomEvenement){

        $dqlresult=$this->getEntityManager()
            ->createQuery("SELECT e FROM MarketingBundle:Evenement e where e.nomEvenement='$nomEvenement'");
        return $dqlresult->getResult();
    }



}