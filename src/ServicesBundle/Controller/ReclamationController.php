<?php

namespace ServicesBundle\Controller;

use PepiniereBundle\Entity\User;
use PepiniereBundle\PepiniereBundle;
use ServicesBundle\Entity\Reclamation;
use ServicesBundle\Form\ReclamationOtherType;
use ServicesBundle\Form\ReclamationSearchType;
use ServicesBundle\Form\ReclamationType;
use ServicesBundle\ServicesBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTimeValidator;

class ReclamationController extends Controller
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
        return $this->render('@Services/Reclamation/affiche.html.twig', array(
            // ...
        ));
    }





    public function AddAction($type,Request $request)
    {
        $reclamation=new Reclamation();
        $form=$this->createForm(ReclamationType::class,$reclamation);
        $form=$form->handleRequest($request);
        if($form->isValid())
        {
            $reclamation->setUser($this->getUserr());
            $reclamation->setType($type);
            $reclamation->setDate(new \DateTime('now'));
            $reclamation->setState(0);
            $reclamation->setImportant(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute('afficheReclamation');
        }
        return $this->render('@Services/Reclamation/Add.html.twig', array(
            'form'=>$form->createView(),'type'=>$type
        ));
    }
    public function UpdateAction($id, Request $request,$type)
    {
        $em=$this->getDoctrine()->getManager();
        $modele = $em->getRepository(Reclamation::class)->find($id);
        if($type == "Autre")
        {
            $form=$this->createForm(ReclamationOtherType::class,$modele);
            $form=$form->handleRequest($request);
            if ($form->isValid()){
                $em->flush();
                return $this->redirectToRoute('ListReclamation');
            }
            return $this->render('@Services/Reclamation/AddOther.html.twig', array(
                'form'=>$form->createView()
            ));
        }
        else
        {
            $form=$this->createForm(ReclamationType::class,$modele);
            $form=$form->handleRequest($request);
            if ($form->isValid()){
                $em->flush();
                return $this->redirectToRoute('ListReclamation');
            }
            return $this->render('@Services/Reclamation/Add.html.twig', array(
                'form'=>$form->createView(),'type'=>$type
            ));
        }

    }
    public function AddOtherAction($type,Request $request)
    {
        $reclamation=new Reclamation();
        $form=$this->createForm(ReclamationOtherType::class,$reclamation);
        $form=$form->handleRequest($request);
        if($form->isValid())
        {
            $reclamation->setUser($this->getUserr());
            $reclamation->setType($type);
            $reclamation->setDate(new \DateTime('now'));
            $reclamation->setUserToClaim("NONE");
            $reclamation->setState(0);
            $reclamation->setImportant(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute('afficheReclamation');
        }
        return $this->render('@Services/Reclamation/AddOther.html.twig', array(
            'form'=>$form->createView()
        ));
    }


    public function ListAction(Request $request)
    {//   ListFindBySubjectAndUserReclamed($type,$user,$subject)

        $req=new Reclamation();
        $form=$this->createForm(ReclamationSearchType::class,$req);
        $form=$form->handleRequest($request);
        $data=$form->getData();
        $myreclamation=$this->getDoctrine()->getRepository(Reclamation::class)->findMyReclamation($this->getUserr()->getId());
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            if($data->gettype()=="Tout")
                $type="";
            else
                $type=$data->gettype();
            $myreclamation=$em->getRepository(Reclamation::class)
                ->ListFindByTypeUserReclamed($type);
            return $this->render('@Services/Reclamation/List.html.twig', array(
                'myreclamation'=>$myreclamation,'form'=>$form->createView()
            ));
        }

        return $this->render('@Services/Reclamation/List.html.twig', array(
            'myreclamation'=>$myreclamation,'form'=>$form->createView()
        ));
    }



    public function DeleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $rec=$em->getRepository(Reclamation::class)->find($id);
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('ListReclamation');

    }
    public function searchreclamationemptyAction(Request $request,$valeur)
    {
        $em=$this->getDoctrine()->getManager();
        $valeur=trim($valeur);

        $ssearch=$em->getRepository('ServicesBundle:Reclamation')->reclamationSearchajax($valeur);
        if($ssearch)
        {
            foreach ($ssearch as $s)
            {
                $resultats[]=$s->getUsername();
             }
        }
        else
        {
            $resultats=null;
        }

        $response=new JsonResponse();
        return $response->setData(array('valeur'=>$resultats));

    }





/////////////////////////////////////BACKOFFICE**********************BACKEND////////////////////////////////////////////////////////////

    public function REC_Trash()
    {
        return $this->getDoctrine()->getRepository(Reclamation::class)->CountTrash();
    }
    public function REC_Readed()
    {
        return $this->getDoctrine()->getRepository(Reclamation::class)->CountReaded();
    }
    public function REC_NOT_Readed()
    {
        return $this->getDoctrine()->getRepository(Reclamation::class)->CountNotReaded();
    }
    public function REC_Important()
    {
        return $this->getDoctrine()->getRepository(Reclamation::class)->CountImportant();
    }

    public function ShowAction()
    {
        $myreclamation=$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $nbread=$this->REC_Readed();
        $nbread=$nbread[0];
        $nbunread=$this->REC_NOT_Readed();
        $nbunread=$nbunread[0];
        $nbImportant=$this->REC_Important();
        $nbImportant=$nbImportant[0];
        $nbTrash=$this->REC_Trash();
        $nbTrash=$nbTrash[0];
        $equi=new \DateTime('now');


        return $this->render('@Services/BO/Reclamation.html.twig', array(
            "user"=>$this->getUserr(),'myreclamation'=>$myreclamation,"d"=>$equi,"nbread"=>$nbread,"nbunread"=>$nbunread,"list"=>"All","important"=>$nbImportant
        ,"trash"=>$nbTrash
        ));
    }
    public function ShowReadedAction()
    {
        $myreclamation=$this->getDoctrine()->getRepository(Reclamation::class)->findReaded();
        $nbread=$this->REC_Readed();

        $nbread=$nbread[0];
        $nbunread=$this->REC_NOT_Readed();
        $nbunread=$nbunread[0];
        $nbImportant=$this->REC_Important();
        $nbImportant=$nbImportant[0];
        $nbTrash=$this->REC_Trash();
        $nbTrash=$nbTrash[0];
        $equi=new \DateTime('now');


        return $this->render('@Services/BO/Reclamation.html.twig', array(
            "user"=>$this->getUserr(),'myreclamation'=>$myreclamation,"d"=>$equi,"nbread"=>$nbread,"nbunread"=>$nbunread,"list"=>"Readed","important"=>$nbImportant
        ,"trash"=>$nbTrash
        ));
    }
    public function ShowUNReadedAction()
    {
        $myreclamation=$this->getDoctrine()->getRepository(Reclamation::class)->findUNReaded();
        $nbread=$this->REC_Readed();

        $nbread=$nbread[0];
        $nbunread=$this->REC_NOT_Readed();
        $nbunread=$nbunread[0];
        $nbImportant=$this->REC_Important();
        $nbImportant=$nbImportant[0];
        $nbTrash=$this->REC_Trash();
        $nbTrash=$nbTrash[0];
        $equi=new \DateTime('now');


        return $this->render('@Services/BO/Reclamation.html.twig', array(
            "user"=>$this->getUserr(),'myreclamation'=>$myreclamation,"d"=>$equi,"nbread"=>$nbread,"nbunread"=>$nbunread,"list"=>"UnReaded","important"=>$nbImportant
        ,"trash"=>$nbTrash
        ));
    }
    public function ShowImportantAction()
    {
        $myreclamation=$this->getDoctrine()->getRepository(Reclamation::class)->findImportant();
        $nbread=$this->REC_Readed();
        $nbread=$nbread[0];
        $nbunread=$this->REC_NOT_Readed();
        $nbunread=$nbunread[0];
        $nbImportant=$this->REC_Important();
        $nbImportant=$nbImportant[0];
        $nbTrash=$this->REC_Trash();
        $nbTrash=$nbTrash[0];
        $equi=new \DateTime('now');


        return $this->render('@Services/BO/Reclamation.html.twig', array(
            "user"=>$this->getUserr(),'myreclamation'=>$myreclamation,"d"=>$equi,"nbread"=>$nbread,"nbunread"=>$nbunread,"list"=>"Important","important"=>$nbImportant
        ,"trash"=>$nbTrash
        ));
    }
    public function ShowTrashAction()
    {
        $myreclamation=$this->getDoctrine()->getRepository(Reclamation::class)->findTrash();
        $nbread=$this->REC_Readed();
        $nbread=$nbread[0];
        $nbunread=$this->REC_NOT_Readed();
        $nbunread=$nbunread[0];
        $nbImportant=$this->REC_Important();
        $nbImportant=$nbImportant[0];
        $nbTrash=$this->REC_Trash();
        $nbTrash=$nbTrash[0];
        $equi=new \DateTime('now');


        return $this->render('@Services/BO/Reclamation.html.twig', array(
            "user"=>$this->getUserr(),'myreclamation'=>$myreclamation,"d"=>$equi,"nbread"=>$nbread,"nbunread"=>$nbunread,"list"=>"Trash","important"=>$nbImportant
        ,"trash"=>$nbTrash
        ));
    }

    public function ShowOneAction($id)
    {

        $nbread=$this->REC_Readed();
        $nbread=$nbread[0];
        $nbunread=$this->REC_NOT_Readed();
        $nbunread=$nbunread[0];
        $nbImportant=$this->REC_Important();
        $nbImportant=$nbImportant[0];
        $nbTrash=$this->REC_Trash();
        $nbTrash=$nbTrash[0];
        $myreclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $modele = $em->getRepository(Reclamation::class)->find($id);
        $modele->setState(1);
        $em->flush();

        return $this->render('@Services/BO/showOneReclamation.html.twig', array(
            "user"=>$this->getUserr(),'myreclamation'=>$myreclamation,"nbread"=>$nbread,"nbunread"=>$nbunread,"list"=>"Important","important"=>$nbImportant
        ,"trash"=>$nbTrash
        ));
    }

    public function SetAsImportantAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $modele = $em->getRepository(Reclamation::class)->find($id);
        $modele->setImportant(1);
        $em->flush();

        return $this->redirectToRoute('ShowReclamationBO_UNReaded');
    }
    public function SendToTrashAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $modele = $em->getRepository(Reclamation::class)->find($id);
        $modele->setTrash(1);
        $equi=new \DateTime('now');
        $modele->setDatetrash($equi);
        $em->flush();

        return $this->redirectToRoute('ShowReclamationBO_UNReaded');
    }

    public function ProfileReclamationAction($username)
    {
        $nbread=$this->REC_Readed();
        $nbread=$nbread[0];
        $nbunread=$this->REC_NOT_Readed();
        $nbunread=$nbunread[0];
        $nbImportant=$this->REC_Important();
        $nbImportant=$nbImportant[0];
        $nbTrash=$this->REC_Trash();
        $nbTrash=$nbTrash[0];

        $Profile=$this->getDoctrine()->getRepository(Reclamation::class)->findProfile($username);
        $Profile=$Profile[0];
        return $this->render('@Services/BO/Profile.html.twig', array(
            "user"=>$this->getUserr(),"nbread"=>$nbread,"nbunread"=>$nbunread,"list"=>"Important","important"=>$nbImportant
        ,"trash"=>$nbTrash,"profile"=>$Profile
        ));
    }


    public function BanUserAction($id)
    {

        $em=$this->getDoctrine()->getManager();
        $modele = $em->getRepository(User::class)->find($id);
        if($modele->getNbBan()<3) {
            $modele->setEnabled(0);
            $modele->setNbBan($modele->getNbBan() + 1);
        }
        else
        {

              $em->remove($modele);

        }
        $em->flush();

        return $this->redirectToRoute('ShowReclamationBO_UNReaded');
    }
    public function DeleteBOAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $rec=$em->getRepository(Reclamation::class)->find($id);
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('ShowReclamationBO_Trash');

    }




    public function auto_unban_user()
    {
        $date_today=new \DateTime('now');
        $emm=$this->getDoctrine()->getManager();
        $modele = $emm->getRepository(Reclamation::class)->findBanned();
        foreach ($modele as $r)
        {
            $nbban=$r->getNbBan();
            $lastlogin=$r->getLastLogin();
            $diff=date_diff($date_today,$lastlogin)->d;
            if($nbban == 1)
            {
                if($diff > 7)
                {
                    $r->setEnabled(1);
                    $emm->persist($r);
                }
            }
            elseif ($nbban == 2)
            {
                if($diff > 19)
                {
                    $r->setEnabled(1);
                    $emm->persist($r);
                }
            }
            else
            {
                if($diff > 29)
                {
                    $r->setEnabled(1);
                    $emm->persist($r);
                }
            }

        }
        $emm->flush();
    }
    public function auto_delete_from_trash()
    {
        $date_today=new \DateTime('now');
        $em=$this->getDoctrine()->getManager();

          $modele = $em->getRepository(Reclamation::class)->findTrash();
                foreach ($modele as $r)
                {
                    if($r->getTrash()==1)
                    {
                        $datetrash=$r->getDatetrash();
                        $diff=date_diff($date_today,$datetrash)->d;
                        if($diff >9)
                        {

                            $this->DeleteBOAction($r->getId());
                        }

                    }
                }

         // $modele->setNbBan($modele->getNbBan() + 1);

    }

    public function searchuseremptyAction(Request $request,$valeur)
    {
        $em=$this->getDoctrine()->getManager();
        $valeur=trim($valeur);

        $ssearch=$em->getRepository('ServicesBundle:Reclamation')->usersSearchajax($valeur);
        if($ssearch)
        {
            $resultats=array();



            foreach ($ssearch as $s)
            {
                $resultats[$s->getId()]["id"]=$s->getId();
                $resultats[$s->getId()]["username"]=$s->getUsername();
                $resultats[$s->getId()]["date"]=$s->getLastLogin();
                $resultats[$s->getId()]["status"]=$s->getNbBan();
                $resultats[$s->getId()]["email"]=$s->getEmail();
            }
        }
        else
        {
            $resultats=null;
        }

        $response=new JsonResponse();
        return $response->setData(array('valeur'=>$resultats));

    }
    public function ListUtilisateurAction()
        {

        $users=$this->getDoctrine()->getRepository(Reclamation::class)->findNotBanned();

        return $this->render('@Services/BO/user.html.twig', array(
            "user"=>$this->getUserr(),"list"=>$users
        ));


        }

    public function ListUtilisateurBannedAction()
    {
        $users=$this->getDoctrine()->getRepository(Reclamation::class)->findBanned();

        return $this->render('@Services/BO/user.html.twig', array(
            "user"=>$this->getUserr(),"list"=>$users
        ));
    }




}
