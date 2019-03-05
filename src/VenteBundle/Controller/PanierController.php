<?php

namespace VenteBundle\Controller;

use ProduitBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use VenteBundle\Entity\Livraison;
use VenteBundle\Entity\Panier;
use VenteBundle\Form\PanierType;
use VenteBundle\VenteBundle;

class PanierController extends Controller
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
        $panier = $this ->getDoctrine()->getRepository(Panier::class)->findPanierByUser($this->getUserr()->getId());
        if ($panier){


        return $this->render('@Vente/Panier/affiche.html.twig', array(
            'panier' => $panier ,

        ));
        }
    else{return $this->redirectToRoute('pepiniere_homepage');}
    }
    public function ajoutaupanierAction($id)
    {
        $panier = new Panier();
        $produit = $this ->getDoctrine()->getRepository(Produit::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $panier->setQtpanier(1);
        $panier->setValid(0);
        $panier->setUser($this->getUserr());
        $panier->setProduit($produit);
        $em->persist($panier);
        $em->flush();
        return $this->redirectToRoute('afficheShop');}





    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $panier = $em->getRepository(Panier::class)->find($id);
        $em->remove($panier);
        $em->flush();
        return $this->redirectToRoute('affichePanier');

    }

//AfficheFacture = Valider panier
    public function AfficheFactureAction()
    {
        $fac = $this ->getDoctrine()->getRepository(Panier::class)->findAll();

        return $this->render('@Vente/Panier/facture.html.twig', array(
            'fac' => $fac,

        ));
    }
    public function ValiderPanierAction(Request $request)
    {   $em = $this->getDoctrine()->getManager();

        $qtp = $em->getRepository(Panier::class)->findPanierByUser($this->getUserr()->getId());
        $DateNow = new \DateTime('now');

        foreach ( $qtp  as $p)
        {
            $l = new Livraison();
            $qt = $em->getRepository(Panier::class)->getByproduit($p->getProduit()->getId());
            $qt=$qt[0];
            $qt->setQuantite($qt->getQuantite() - $p->getQtpanier());
            $p->setValid(1);
            $em->persist($qt);


           $l->setDateDepart($DateNow);
            $l->setProduit($qt);
            $l->setPanier($p);
            $em->persist($l);

           }



        $em->flush();
        return $this->redirectToRoute('affichePanier');
    }


    public function updateqteAction($id,Request $request)
    {


        $em=$this->getDoctrine()->getManager();
        $modele=$em->getRepository(Panier::class)->find($id);


        $form=$this->createForm(PanierType::class,$modele);
        $form=$form->handleRequest($request);
       if ($form->isValid())
       {
           $em->persist($modele);
           $em->flush();
       return $this->redirectToRoute('affichePanier');
       }

        return $this->render('@Vente/Panier/updateqte.html.twig', array(
            'form'=>$form->createView(),
            "user"=>$this->getUserr(),"panier"=>$modele
        ));
    }













}
