<?php

namespace ProduitBundle\Controller;

use ProduitBundle\Entity\Produit;
use ProduitBundle\Form\ProduitType;
use ProduitBundle\Form\PType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\User;
use Symfony\Component\Validator\Constraints\DateTime;

class ShopController extends Controller
{
    public function afficheAction()
    {
        return $this->render('@Produit/Shop/affiche.html.twig', array(
            // ...
        ));
    }

    public function getUserr()
    {
        $user=null;
        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

        }
        return $user;
    }

    public function index3Action()
    {



        return $this->render('@Produit/Shop/product-detail.html.twig', array(
            "user"=>$this->getUserr()
        ));
    }

    public function ajouterAction(Request $request)
    {


        return $this->render('@Produit/BO/Ajouter.html.twig', array(
            "user"=>$this->getUserr()
        ));
    }

    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm('ProduitBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()&& $produit->getPrix()>0 && $produit->getPrix()<999 && $produit->getQuantite()>0 ) {
            $em = $this->getDoctrine()->getManager();
            $produit->setUser($this->getUserr());
            $em->persist($produit);
            $em->flush();


            return $this->redirectToRoute('afficheProduitUserBO', array('id' => $produit->getId()));
        }

        return $this->render('@Produit/BO/Ajouter.html.twig', array(
            'categorie' => $produit,
            'form' => $form->createView(),
            'user'=>$this->getUserr()
        ));
    }

    public function showAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listeProduit = $em->getRepository('ProduitBundle:Produit')->sortByPrixASC();
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            6);
        return $this->render('@Produit/BO/produit.html.twig', array(
            "produit" => $produit,
            'user'=>$this->getUserr()
        ));
    }
    public function showFOAction(Request $request)
    {
        $date=new DateTime();
        $em = $this->getDoctrine()->getManager();
        $listeProduit = $em->getRepository('ProduitBundle:Produit')->findAll();
        //$listeProduit2 = $em->getRepository('ProduitBundle:Produit')->Rechdate($date);
        $categorie = $em->getRepository("ProduitBundle:Categorie")->findAll();
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            6);

        $prod=new Produit();
        $form= $this->createForm(PType::class,$prod);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $nameprod=$form->getData()->getNom();

            $listeProduit = $em->getRepository('ProduitBundle:Produit')->SearchByName($nameprod);
            $produit  = $this->get('knp_paginator')->paginate(
                $listeProduit,
                $request->query->get('page', 1),
                6);

            return $this->render('@Produit/Shop/affiche.html.twig', array(
                "produit" => $produit,

                'user'=>$this->getUserr(),
                'categorie' => $categorie,
                "form"=>$form->createView()
            ));
        }



        return $this->render('@Produit/Shop/affiche.html.twig', array(
            "produit" => $produit,

            'user'=>$this->getUserr(),
            'categorie' => $categorie,
            "form"=>$form->createView()
        ));
    }




    public function showFOTrieAsecAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listeProduit = $em->getRepository('ProduitBundle:Produit')->sortByPrixASC();
        $categorie = $em->getRepository("ProduitBundle:Categorie")->findAll();
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            6);
        return $this->render('@Produit/Shop/affiche.html.twig', array(
            "produit" => $produit,
            'user'=>$this->getUserr(),
            'categorie' => $categorie,
        ));
    }

    public function showFOTrieDescAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listeProduit = $em->getRepository('ProduitBundle:Produit')->sortByPrixDES();
        $categorie = $em->getRepository("ProduitBundle:Categorie")->findAll();
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            6);
        return $this->render('@Produit/Shop/affiche.html.twig', array(
            "produit" => $produit,
            'user'=>$this->getUserr(),
            'categorie' => $categorie,
        ));
    }

    public function updateAction($id,Request $request)
    {
        //1.preparation de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2.preparation de notre objet
        $produit=$em->getRepository(Produit::class)->find($id);
        //3.preparation de notre form
        $form=$this->createForm(ProduitType::class,$produit);
        //5.recuperation du formulaire
        $form=$form->handleRequest($request);
        //6.validation du formulaire
        if ($form->isValid()&& $produit->getPrix()>0 && $produit->getPrix()<999 && $produit->getQuantite()>0 )
        {   //7.update dans la BD
            $em->flush();
            //8.rediraction
            return $this->redirectToRoute('afficheProduitBO');
        }
        //4.Envoi du form à l'utilisateur
        return $this->render('@Produit/BO/modifier.html.twig', array(
            'form'=>$form->createView(),
            'user'=>$this->getUserr(),
        ));
    }

    public function rechercheAction(Request $request){
        $produit = new Produit();
        $form = $this->createForm(PType::class, $produit);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $produit = $this->getDoctrine()->getRepository(Produit::class)->findByDQL();
        } else {

            $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        }
        return $this->render('@ProduitBundle/BO/produit.html.twig', array('produit' => $produit,
            'user'=>$this->getUserr(),
            'form' => $form->createView()));
    }

    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository("ProduitBundle:Produit")->find($id);
        $produit->getCategorie(null);
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('afficheProduitBO');

    }

    public function detailAction($id,Request $request)
    {
        //1.preparation de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2.preparation de notre objet
        $produit=$em->getRepository(Produit::class)->find($id);

        //4.Envoi du form à l'utilisateur
        return $this->render('@Produit/Shop/product-detail.html.twig', array(
            'produit'=>$produit,
            'user'=>$this->getUserr(),
        ));
    }


    public function showFOPHAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $listeProduit = $em->getRepository('ProduitBundle:Produit')->sortByCriteria();
        $categorie = $em->getRepository("ProduitBundle:Categorie")->findAll();
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            6);

        return $this->render('@Produit/Shop/affiche.html.twig', array(
            "produit" => $produit,
            'user'=>$this->getUserr(),
            'categorie' => $categorie,
        ));
    }
    public function showUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iduser= $this->getUserr();
        $listeProduit = $em->getRepository('ProduitBundle:Produit')->findBy(array('User'=>$iduser));
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            6);
        return $this->render('@Produit/BO/produit.html.twig', array(
            "produit" => $produit,
            'user'=>$this->getUserr()
        ));
    }
    public function geProduitSelonCatAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idcategorie = $em->getRepository('ProduitBundle\Entity\Categorie')->find($id);
        $categorie = $em->getRepository("ProduitBundle:Categorie")->findAll();

        $listeProduit = $em->getRepository('ProduitBundle:Produit')->findBy(array('Categorie'=>$idcategorie));
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            6);

        return $this->render('@Produit/Shop/affiche.html.twig', array(
            "produit" => $produit,
            'user'=>$this->getUserr(),
            'categorie' => $categorie,
        ));
    }

    public function geProduitsearchAction(Request $request)
    {
   /*     echo $id;
        die();*/
   $value=$request->get('search');

        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("ProduitBundle:Categorie")->findAll();

        $listeProduit = $em->getRepository('ProduitBundle:Produit')->search($value);
        $produit  = $this->get('knp_paginator')->paginate(
            $listeProduit,
            $request->query->get('page', 1),
            6);

        return $this->render('@Produit/Shop/affiche.html.twig', array(
            "produit" => $produit,
            'user'=>$this->getUserr(),
            'categorie' => $categorie,
        ));
    }
}
