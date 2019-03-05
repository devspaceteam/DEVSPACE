<?php

namespace VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use VenteBundle\Entity\Panier;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $snappy = $this->get('knp_snappy.pdf');
        $fac = $this ->getDoctrine()->getRepository(Panier::class)->findAll();

        $html = $this->renderView('@Vente/Panier/facturee.html.twig',array(
            'title' => 'Hello World', 'fac' => $fac
        ));

        $filename = 'myFirstSnappyPDF';

        return new Response( $snappy->getOUtputFromHtml($html,array(

            'enable-javascript' => true,
            'javascript-delay' => 1000,
            'no-stop-slow-scripts' => true,
            'no-background' => false,
            'lowquality' => false,
            'encoding' => 'utf-8',
            'images' => true,
            'cookie' => array(),
            'dpi' => 300,
            'image-dpi' => 300,
            'enable-external-links' => true,
            'enable-internal-links' => true
        )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )


        );
    }
}
