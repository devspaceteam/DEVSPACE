<?php

namespace ProduitBundle\Controller;

use ProduitBundle\Entity\Categorie;
use ProduitBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Categorie controller.
 *
 */
class CategorieController extends Controller
{
    /**
     * Lists all categorie entities.
     *
     */
    public function getUserr()
    {
        $user=null;
        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
        }
        return $user;
    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('ProduitBundle:Categorie')->findAll();

        return $this->render('@Produit/BO/Categorie.html.twig', array(
            'categories' => $categories,
            'user'=>$this->getUserr()
        ));
    }

    /**
     * Creates a new categorie entity.
     *
     */
    public function newAction(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm('ProduitBundle\Form\CategorieType', $categorie);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();


            return $this->redirectToRoute('afficheCategorieBO', array('id' => $categorie->getId()));
        }

        return $this->render('@Produit/BO/AjouterCategorie.html.twig', array(
            'categorie' => $categorie,
            'form' => $form->createView(),
            'user'=>$this->getUserr()
        ));
    }

    /**
     * Finds and displays a categorie entity.
     *
     */
    public function showAction()
    {

        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("ProduitBundle:Categorie")->findAll();
        return $this->render('@Produit/BO/Categorie.html.twig', array(
            "categorie" => $categorie,
            'user'=>$this->getUserr()
        ));
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     */
    public function editAction(Request $request, Categorie $categorie)
    {
        $deleteForm = $this->createDeleteForm($categorie);
        $editForm = $this->createForm('ProduitBundle\Form\CategorieType', $categorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_edit', array('id' => $categorie->getId()));
        }

        return $this->render('categorie/edit.html.twig', array(
            'categorie' => $categorie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a categorie entity.
     *
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $Categorie = $em->getRepository("ProduitBundle:Categorie")->find($id);
        $em->remove($Categorie);
        $em->flush();
        return $this->redirectToRoute('afficheCategorieBO');

    }

    /**
     * Creates a form to delete a categorie entity.
     *
     * @param Categorie $categorie The categorie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categorie $categorie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categorie_delete', array('id' => $categorie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    public function updateAction($id,Request $request)
    {
        //1.preparation de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2.preparation de notre objet
        $Categorie=$em->getRepository(Categorie::class)->find($id);
        //3.preparation de notre form
        $form=$this->createForm(CategorieType::class,$Categorie);
        //5.recuperation du formulaire
        $form=$form->handleRequest($request);
        //6.validation du formulaire
        if ($form->isValid())
        {   //7.update dans la BD
            $em->flush();
            //8.rediraction
            return $this->redirectToRoute('afficheCategorieBO');
        }
        //4.Envoi du form à l'utilisateur
        return $this->render('@Produit/BO/modifierCategorie.html.twig', array(
            'form'=>$form->createView(),
            'user'=>$this->getUserr(),
        ));
    }

}
