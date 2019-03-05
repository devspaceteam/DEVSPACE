<?php

namespace GrapheBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


use MarketingBundle\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexxxAction()
    {
        $pieChart = new PieChart();
        $em= $this->getDoctrine();
        $classes = $em->getRepository(Evenement::class)->findAll();
        $totalEtudiant=0;



foreach($classes as $classe) {
    $totalEtudiant=$totalEtudiant+$classe->getnbrcado();
}
$data= array();
$stat=['nomEvenement', 'nbrcado'];
$nb=0;
array_push($data,$stat);
foreach($classes as $classe) {
    $stat=array();
    array_push($stat,$classe->getnomEvenement(),(($classe->getnbrcado()) *100)/$totalEtudiant);
    $nb=($classe->getnbrcado() *100)/$totalEtudiant;
    $stat=[$classe->getnomEvenement(),$nb];
    array_push($data,$stat);
}
$pieChart->getData()->setArrayToDataTable(
    $data
);
$pieChart->getOptions()->setTitle('Pourcentages des cadeaux par evenement');
$pieChart->getOptions()->setHeight(500);
$pieChart->getOptions()->setWidth(900);
$pieChart->getOptions()->getTitleTextStyle()->setBold(true);
$pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
$pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
$pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
$pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
return $this->render('@Graphe\Default\index.html.twig', array('piechart' => $pieChart));
}






}
