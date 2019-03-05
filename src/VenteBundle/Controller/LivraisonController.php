<?php

namespace VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use VenteBundle\Entity\Livraison;

class LivraisonController extends Controller
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
    public function AfficheLivraisonAction()
    {
        $livraison = $this ->getDoctrine()->getRepository(Livraison::class)->findAll();

        return $this->render('@Vente/Livraison/affiche.html.twig', array(
            'livraison' => $livraison ,

        ));
    }


    public function deleteLivraisonAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $livraison = $em->getRepository(Livraison::class)->find($id);
        $em->remove($livraison);
        $em->flush();
        return $this->redirectToRoute('AfficheLivraison');

    }
    public function index8Action()
    {
        return $this->render('@Vente/BO/livraison.html.twig', array(
            "user"=>$this->getUserr()
        ));
    }
}
