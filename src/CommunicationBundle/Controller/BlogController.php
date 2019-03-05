<?php

namespace CommunicationBundle\Controller;


use CommunicationBundle\Entity\BlogPost;
use CommunicationBundle\Entity\Category;
use CommunicationBundle\Entity\Comment;
use CommunicationBundle\Form\BlogPostType;
use CommunicationBundle\Form\BlogPostType1;
use CommunicationBundle\Form\CategoryType;
use CommunicationBundle\Form\CommentType;
use PepiniereBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Expalmer\PhpBadWords\PhpBadWords ;


class BlogController extends Controller
{
    public function AfficheAction()
    {
        return $this->render('@Communication/Blog/affiche.html.twig', array(
            // ...
        ));
    }
    public function getUserr()
    {

        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

        }
        return $user;
    }

    public function index6Action(Request $request)
    {
//        return $this->render('@Communication/BO/Blog.html.twig', array(
//            "user"=>$this->getUserr()
//        ));

       $em = $this->getDoctrine()->getManager();
       $blogposts = $em->getRepository(BlogPost::class)->findBy([], ['createdAt' => 'DESC']);

        $dql   = "SELECT a FROM CommunicationBundle:BlogPost a";
        $query = $em->createQuery($dql);

        $comments = array();
        foreach ($blogposts AS $p){

           $comments[$p->getId()]["id"] = $p->getId();
            $comments[$p->getId()]["title"] = $p->getTitle();
            $comments[$p->getId()]["body"] = $p->getBody();
            $comments[$p->getId()]["slug"] = $p->getSlug();
            $comments[$p->getId()]["createdAt"] = $p->getCreatedAt();
           $xxxx=$em->getRepository(Comment::class)->CommentsCount($p->getId());

            $comments[$p->getId()]["nb"] = $xxxx;


            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                2/*limit per page*/
            );

        }
        return $this->render('@Communication/Blog/index.html.twig', array(
            "blogposts"=> $blogposts,"comments"=> $comments ,"pagination" => $pagination    
        ));
    }



    public function new_emptyAction(Request $request, $valeur)
    {


        $em=$this->getDoctrine()->getManager();
        $valeur=trim($valeur);

        $ssearch=$em->getRepository('CommunicationBundle:BlogPost')->postSearchajax($valeur);
        if($ssearch)
        {
            foreach ($ssearch as $s)
            {
                $resultats[]=$s->getName();
            }
        }
        else
        {
            $resultats=null;
        }

        $response=new JsonResponse();
        return $response->setData(array('valeur'=>$resultats));


    }

    public function newAction(Request $request)
    {
        $post = new BlogPost();
        $form_category = $this->createForm(CategoryType::class);

        $form_category->handleRequest($request);

        if ($form_category->isValid()) {

            $post = $form_category->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return  $this->redirectToRoute('afficheBlog');
        }

        $form = $this->createForm(BlogPostType::class,$post);

        $form->handleRequest($request);

            if ($form->isValid()) {

               $s = $form->getData();
               $s = $s->getCategoryName();


                $category = new Category();

                $cate = $this->getDoctrine()->getManager()->getRepository(Category::class)->findOneBy(array('name'=>$s));
                $post->setCategory($cate);

                $post->setCreatedAt(new \DateTime());
                $post->setUpdatedAt(new \DateTime());
                $post->setUser($this->getUserr());


                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
                return  $this->redirectToRoute('afficheBlog');
            }


        return $this->render('@Communication/Blog/new.html.twig', array(
            'form' => $form->createView(), 'form_category' => $form_category->createView(),
        ));
    }

    public function showAction(Request $request , $id)
    {
        $blogpost = $this->getDoctrine()->getManager()->getRepository(BlogPost::class)->find($id);

        $comment = $this->getDoctrine()->getManager()->getRepository(BlogPost::class)->findAllCommentsPerPost($id);

        $dt=new \DateTime('now');
        $comm=new Comment();
        $form = $this->createForm(CommentType::class, $comm);
        $form->handleRequest($request);


        //Related postes



        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $comm->setUsername($this->getUserr()->getUsername());
            $comm->setCreatedAt($dt);
            $comm->setPost($blogpost);

            //Filter Comment
//            $myDictionary = array(
//                array("ass","among"),
//                "anal",
//                "butt"
//            );

            $myText = $comm->getContent();


            $badwords = new PhpBadWords();
            $badwords->setDictionaryFromFile( __DIR__ . "/dictionary.php" )
                ->setText( $myText );


            if ( $badwords->check()  == true  ){

               $this->addFlash('notice', 'Unable to submit this comment! you can not use bad words');

            } else {

                $em->persist($comm);
                $em->flush();

                return $this->redirectToRoute('blog_show', ['slug' => $blogpost->getSlug(),'id'=>$id]);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $user_id=$blogpost->getUser()->getId();
        $userPostCount = $em->getRepository(BlogPost::class)
            ->UserPostsCount($user_id);
        $userPostCount=$userPostCount[0];

        $user = $blogpost->getUser();


        $related = $this->getDoctrine()->getManager()->getRepository(BlogPost::class)->createQueryWithCategory($blogpost->getCategory()->getId());


        return $this->render('@Communication/Blog/show.html.twig', array(
            "blogpost" => $blogpost
            ,"comment"=>$comment,
            'form'=>$form->createView()
            , 'user' => $user,
            'userPostCount' => $userPostCount,
            'related' => $related

        ));
    }

    public function editAction(Request $request,  $id)
    {

        $em=$this->getDoctrine()->getManager();
        $model=$em->getRepository(BlogPost::class)->find($id);

        $user = $this->getUserr()->getId();

        if (!$model || $user !== $model->getUser()->getId()){

            $this->addFlash('error', 'Unable to edit entry!');

            return $this->redirectToRoute('afficheBlog');

        }

        $form=$this->createForm(BlogPostType1::class,$model);
        $form=$form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('afficheBlog');
        }


        return $this->render('@Communication/Blog/edit.html.twig', array(
            'post' => $id,
            'edit_form' => $form->createView(),

        ));
    }

    public function deleteAction($post)
    {

        $em = $this->getDoctrine()->getManager();
        $blogpost = $em->getRepository(BlogPost::class)->find($post);

        $user = $this->getUserr()->getId();

        if (!$blogpost || $user !== $blogpost->getUser()){

            $this->addFlash('error', 'Unable to remove entry!');

            return $this->redirectToRoute('afficheBlog');

        }

        $em->remove($blogpost);
        $em->flush();

        return $this->redirectToRoute('afficheBlog');

    }



    public function adminDeleteAction($post)
    {

        $em = $this->getDoctrine()->getManager();
        $blogpost = $em->getRepository(BlogPost::class)->find($post);

        $em->remove($blogpost);
        $em->flush();

        return $this->redirectToRoute('afficheBlog');

    }



    public function authorshowmmAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $blogpost = $em->getRepository(User::class)->find($id);

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $blogpost,
        ));


    }
}
