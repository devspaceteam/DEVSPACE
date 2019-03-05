<?php

namespace CommunicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MessageController extends Controller
{

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

    public function AfficheAction()
    {
        return $this->render('@Communication/Message/affiche.html.twig', array(
            // ...
        ));
    }

}
