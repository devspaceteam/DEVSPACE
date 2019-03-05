<?php

namespace CommunicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FAQController extends Controller
{
    public function AfficheAction()
    {
        return $this->render('@Communication/FAQ/affiche.html.twig', array(
            // ...
        ));
    }

    public function getUserr()
    {
        $username=null;
        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $username = $user->getUsername();
        }
        return $username;
    }
    public function index7Action()
    {
        return $this->render('@Communication/BO/FAQ.html.twig', array(
            "user"=>$this->getUserr()
        ));
    }

}
