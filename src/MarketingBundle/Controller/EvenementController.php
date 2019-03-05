<?php

namespace MarketingBundle\Controller;


use MarketingBundle\Entity\Evenement;
use MarketingBundle\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EvenementController extends Controller
{
    public function AfficheAction()
    {
        $date=new \DateTime();
        $em=$this->getDoctrine()->getRepository(Evenement::class);
        $n=$em->computeDQL($date);
        // $n=$em->computeDQLL();
        $poromotion=$this->getDoctrine()->getRepository(Evenement::class)->findAll();

        return $this->render('@Marketing/Evenement/affiche.html.twig', array(
            "user"=>$this->getUserr(),'modeles'=>$poromotion,"d"=>$poromotion,"s"=>$date


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

    public function index4Action()
    {
        return $this->render('@Marketing/BO/Evenement.html.twig', array(
            "user"=>$this->getUserr()
        ));
    }

    public function create_evenAction(Request $request)
    {
        //1-preparation objet vide
        $modele=new Evenement();
        //2-creation formulaire
        $form=$this->createForm(EvenementType::class,$modele);
        //4-recupartion de donne
        $form=$form->handleRequest($request);
        //5-valiation de formulaire
        if($form->isValid())
        {
            $subject="aaaaaa";
            $mail="mohameddhia.soudani@esprit.tn";
            $object=$request->get('form')['object'];
            $username="soudanidhia44@gmail.com";
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($username)
                ->setTo($mail)
                ->setBody($object);
            $this->get('mailer')->send($message);
            $this->get('session')->getFlashBag()->add('notice', 'valide');

            //6-creation entity mannager
            $em=$this->getDoctrine()->getManager();
            //7-sauvgarder les donnes dans orm
            $em->persist($modele);
            //8-sauvgarde les donne dans la base de donne
            $em->flush();
            return $this->redirectToRoute('Readeve');
        }
        //envoi de notre formaulire au utilisateur
        return $this->render("@Marketing/BO/Evenement.html.twig",array("form"=>$form->createView(),'user'=>$this->getUserr()));

    }
    public function ReadeveAction()
    {
        $modeles=$this->getDoctrine()->getRepository(Evenement::class)->findall();
        return $this->render('@Marketing/BO/readeve.html.twig', array(

            "user"=>$this->getUserr(),'modeles'=>$modeles
        ));
    }

    public function updateeveAction($id,Request $request)
    {
        //0-preparation entity manager
        $em=$this->getDoctrine()->getManager();
        //1-preparation de notre objet
        $modele=$em->getRepository(Evenement::class)->find($id);
        //3-preparation de notre ofrm
        $form=$this->createForm(EvenementType::class,$modele);
        //5-recuperation de donne de formulaire de base de donne
        $form=$form->handleRequest($request);
        //6-validation formulaire
        if ($form->isValid())
        {
            //7-update dans base de donnee
            $em->flush();
            //8-redirection
            return $this->redirectToRoute('Readeve');
        }
        //4-envoi form a utilisateur
        return $this->render('@Marketing/BO/updateeve.html.twig', array(
            'form'=>$form->createView(), "user"=>$this->getUserr()
        ));
    }
    public function deleteeveAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $modele=$em->getRepository(Evenement::class)->find($id);
        $em->remove($modele);
        $em->flush();
        return $this->redirectToRoute('Readeve');

    }
    public function sendMailAction(Request $request )
    {
     $subject="aaaaaa";
     $mail="mohameddhia.soudani@esprit.tn";
     $object="aaaabbbb";
     $username="soudanidhia44@gmail.com";
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($username)
            ->setTo($mail)
            ->setBody($object);
        $this->get('mailer')->send($message);
        $this->get('session')->getFlashBag()->add('notice', 'valide');
        return $this->render('@Marketing/Evenement/mail.html.twig');

    }
    public function rechercheAction(Request $request){
        $nomEvenement=$request->get('nomEvenement');
        if(isset($nomEvenement)){
            $modeles=$this->getDoctrine()->getRepository(Evenement::class)->myfindall($nomEvenement);
            return $this->render('@Marketing/BO/readeve.html.twig', array(
                'modeles'=>$modeles, "user"=>$this->getUserr()

            ));
        }
        return $this->render('@Marketing/BO/recherche.html.twig', array(
        "user"=>$this->getUserr()
        ));

    }



}
