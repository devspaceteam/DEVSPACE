<?php

namespace PepiniereBundle\Controller;

use PepiniereBundle\Entity\User;
use PepiniereBundle\PepiniereBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use YourBundle\Entity\Notification;

class DefaultController extends Controller
{



    public function getUserr()
    {
        $user=null;
        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

        }
        return $user;
    }
    public function homeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $listeProduit = $em->getRepository('ProduitBundle:Produit')->findAll();
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            8);
        return $this->render('@Pepiniere/Default/index.html.twig', array(
            "produit" => $produit,
            'user'=>$this->getUserr()
        ));
    }

    public function indexAction()
    {
        return $this->render('@Pepiniere/Default/index.html.twig');
    }




    public function index1Action()
    {


        return $this->render('@Pepiniere/BO/index.html.twig', array(
            "user"=>$this->getUserr()

    ));
    }


    public function index2Action()
    {
        return $this->render('@Pepiniere/BO/Categorie.html.twig', array(
            "user"=>$this->getUserr()
        ));
    }







    public function displayAction()
    {
        $em = $this->getDoctrine()->getManager();


          $notificationNotSeen= $em->getRepository('PepiniereBundle:Notification')->findnotseen();
        $not=count($notificationNotSeen);


        $notification= $em->getRepository('PepiniereBundle:Notification')
            ->searchAll();

        return($this->render('@Pepiniere/Default/notif.html.twig',array(
            'notifications' => $notification,
            'notifnb'=> $not

        )));
    }




    public function setunseennotifoffreclamationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notificationNotSeen= $em->getRepository('PepiniereBundle:Notification')->findnotseen();

        foreach ($notificationNotSeen as $nns)
        {
            $nns->setSeen(1);
            $em->persist($nns);

        }
        $em->flush();

        return $this->redirectToRoute('pepiniere_homepage2');
    }

    public function setunseennotifoffpromotionforoneAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $notificationNotSeen= $em->getRepository('PepiniereBundle:Notification')->find($id);


        $notificationNotSeen->setSeen(1);
        $em->persist($notificationNotSeen);

        $em->flush();

        return $this->redirectToRoute('pepiniere_homepage2');
    }

    public function setunseennotifoffreclamationforoneAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $notificationNotSeen= $em->getRepository('PepiniereBundle:Notification')->find($id);


            $notificationNotSeen->setSeen(1);
            $em->persist($notificationNotSeen);

        $em->flush();

        return $this->redirectToRoute('ShowReclamationBO');
    }


    public function index10Action()
    {
        return $this->render('@Pepiniere/BO/profile.html.twig', array(
            "user"=>$this->getUserr()
        ));
    }
}
