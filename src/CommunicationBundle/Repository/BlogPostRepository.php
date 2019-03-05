<?php

namespace CommunicationBundle\Repository;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

/**
 * BlogPostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlogPostRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Add a fetchmode Eager for categories
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilderWithCategory () {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.category", "c")
            ->addSelect("c")
            ->orderBy("p.createdAt", "DESC");
    }

    public function createQueryBuilderWithUser () {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.user", "u")
            ->addSelect("u")
            ->orderBy("p.createdAt", "DESC");
    }

    public function createQueryBuilderWithUserAndCategory () {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.category", "c")
            ->addSelect("c")
            ->leftJoin("p.user", "u")
            ->addSelect("u")
            ->orderBy("p.createdAt", "DESC");
    }

    /**
     * @return array
     */
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(

                'SELECT p FROM CommunicationBundle:BlogPost p ORDER BY p.title ASC'
            )
            ->getResult();
    }
    public function findAllCommentsPerPost($id)
    {
        return $this->getEntityManager()
            ->createQuery(

                "SELECT p FROM CommunicationBundle:Comment p WHERE p.post IN 
                  (SELECT x FROM CommunicationBundle:BlogPost x WHERE x.id = '$id')"
            )
            ->getResult();
    }

    public function createQueryWithCategory($id)
    {
        return $this->getEntityManager()
            ->createQuery(

                "SELECT p FROM CommunicationBundle:BlogPost p 
                WHERE p.category = '$id'  ORDER BY p.createdAt DESC "
            )
            ->setMaxResults(2)->getResult();
    }


    public function UserPostsCount ( $id){

        return $this->getEntityManager()
            ->createQuery(

                "SELECT COUNT(p.id) AS c FROM CommunicationBundle:BlogPost p WHERE p.user = '$id'
                     "
            )
            ->getResult();

    }

    public function postSearchajax ( $valeur){

        return $this->getEntityManager()
            ->createQuery(

                "SELECT p  FROM CommunicationBundle:Category p WHERE p.name LIKE '$valeur%'
                     "
            )
            ->getResult();

    }


    public function getCategoryByName ( $name){

        return $this->getEntityManager()
            ->createQuery(

                "SELECT p  FROM CommunicationBundle:Category p WHERE p.name = '$name'
                     "
            )
            ->getResult();




    }



}
