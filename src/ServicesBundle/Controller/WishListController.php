<?php

namespace ServicesBundle\Controller;

use ProduitBundle\Entity\Produit;
use ServicesBundle\Entity\Wishlist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WishListController extends Controller
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
    public function ListAction()
    {

        $list=$this->getDoctrine()->getRepository(Wishlist::class)->findByUser($this->getUserr()->getId());

        return $this->render('@Services/WishList/ListWishlist.html.twig', array(
            "user"=>$this->getUserr(),"list"=>$list
        ));
    }


    public function DeleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $rec=$em->getRepository(Wishlist::class)->find($id);
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('ListWishList');

    }




    public function AddAction($id,Request $request)
    {
        $produit = new Wishlist();
        $produit->setUser($this->getUserr());
        $produit->setDate(new \DateTime('now'));
        $rec=$this->getDoctrine()->getManager()->getRepository(Produit::class)->find($id);
        $produit->setProduit($rec);
        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

        return $this->redirectToRoute('afficheShop');

    }
    public function SimilarAction($prodid)
    {
        $proddd=$this->getDoctrine()->getRepository(Produit::class)->find($prodid);

        $catego=$proddd->getCategorie()->getId();
        $list=$this->getDoctrine()->getRepository(Wishlist::class)->findByCategorie($catego,$this->getUserr()->getId());

        return $this->render('@Services/WishList/SimilarWishlist.html.twig', array(
            "user"=>$this->getUserr(),"list"=>$list
        ));

    }



    public function searchwishlistemptyAction(Request $request,$valeur)
    {

        $em=$this->getDoctrine()->getManager();
        $valeur=trim($valeur);

        $ssearch=$em->getRepository('ServicesBundle:Wishlist')->WishlistSearchajax($valeur,$this->getUserr()->getId());
        if($ssearch)
        {
            $resultats=array();



            foreach ($ssearch as $s)
            {

                $resultats[$s->getId()]["idp"]=$s->getId();
                $resultats[$s->getId()]["Product"]=$s->getNom();
                $resultats[$s->getId()]["Seller"]=$s->getUser()->getUsername();
            }
        }
        else
        {
            $resultats=null;
        }

        $response=new JsonResponse();
        return $response->setData(array('valeur'=>$resultats));

    }
}
