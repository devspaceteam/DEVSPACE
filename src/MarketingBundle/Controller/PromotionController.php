<?php

namespace MarketingBundle\Controller;

use MarketingBundle\Entity\Promotion;
use ProduitBundle\Entity\Produit;

use MarketingBundle\Form\PromotionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PromotionController extends Controller
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

    public function AfficheAction()
    {
        $date=new \DateTime();
        $em=$this->getDoctrine()->getRepository(Promotion::class);
        $n=$em->computeDQL($date);
       // $n=$em->computeDQLL();
        $poromotion=$this->getDoctrine()->getRepository(Promotion::class)->findAll();

        return $this->render('@Marketing/Promotion/affiche.html.twig', array(
            "pro"=>$poromotion,"d"=>$poromotion, "categorie"=>$poromotion
        ,"s"=>$date
        ));
    }
    public function index5Action()
    {
        return $this->render('@Marketing/BO/Promotion.html.twig', array(
            "user"=>$this->getUserr()
        ));
    }
    public function createAction(Request $request)
    {
        $Voiture=new Promotion();
        $Produitt=new Produit();
        $form=$this->createForm(PromotionType::class,$Voiture);
        $form=$form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();


            $pourcentage=$Voiture->getPourcentage();
            $produit=$Voiture->getProduit();
            $id=$Voiture->getProduit()->getId();
            $prix=$produit->getPrix();
            $prixP=$prix-(($prix*$pourcentage)/100);
            $Voiture->setPrixInitiale($prix);
            $Voiture->setPrixPromo($prixP);


            $em->persist($Voiture,$produit);
            $em->flush();
            return $this->redirectToRoute('Read');
        }
        return $this->render("@Marketing/BO/Promotion.html.twig",array("form"=>$form->createView(),'user'=>$this->getUserr(),
            'voiture'=>$Voiture
            ));

    }
    public function readAction()
    { $modeles=$this->getDoctrine()->getRepository(Promotion::class)->findall();
        return $this->render('@Marketing/BO/read.html.twig', array(
            "user"=>$this->getUserr(),'modeles'=>$modeles

        ));
    }
    public function updatepromAction($id,Request $request)
    {
        //0-preparation entity manager
        $em=$this->getDoctrine()->getManager();
        //1-preparation de notre objet
        $modele=$em->getRepository(Promotion::class)->find($id);
        //3-preparation de notre ofrm
        $form=$this->createForm(PromotionType::class,$modele);
        //5-recuperation de donne de formulaire de base de donne
        $form=$form->handleRequest($request);
        //6-validation formulaire
        if ($form->isValid())
        {
            //7-update dans base de donnee
            $em->flush();
            //8-redirection
            return $this->redirectToRoute('Read');
        }
        //4-envoi form a utilisateur
        return $this->render('@Marketing/BO/updatepro.html.twig', array(
            'form'=>$form->createView(),'user'=>$this->getUserr()
        ));
    }

    public function deletepromAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $modele=$em->getRepository(Promotion::class)->find($id);
        $em->remove($modele);
        $em->flush();
        return $this->redirectToRoute('Read');

    }
}
