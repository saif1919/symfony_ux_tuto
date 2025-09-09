<?php

namespace App\Controller;

use Pentiminax\UX\DataTables\Builder\DataTableBuilderInterface;
use Pentiminax\UX\DataTables\Model\Column;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/home2', name: 'app_homepage')]
    public function index2(ChartBuilderInterface $chartBuilder): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        $chart2 = $chartBuilder->createChart(Chart::TYPE_BAR);

        $chart2->setData([
            'labels' => ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            'datasets' => [
                [
                    'label' =>  '# of Votes',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                    'data' => [12, 19, 3, 5, 2, 3],
                ],
            ],
        ]);

        $chart2->setOptions([
            'scales' => [
                'y' => [
                    'beginAtZero' =>  true
                ],
            ],
        ]);

        $chart3 = $chartBuilder->createChart(Chart::TYPE_BAR);

        $chart3->setData([
            'labels' => ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            'datasets' => [
                [
                    'label' =>  '# of Votes',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                    'data' => [12, 19, 3, 5, 2, 3],
                ],
                [
                    'label' =>  '# of Votes test',
                    'backgroundColor' => 'rgb(100, 99, 132)',
                    'borderColor' => 'rgb(100, 99, 132)',
                    'borderWidth' => 1,
                    'data' => [15, 30, 10, 9, 13, 14],
                ],
            ],
        ]);

        return $this->render('home/index2.html.twig', [
            'chart' => $chart,
            'chart2' => $chart2,
            'chart3' => $chart3,
        ]);
    }

    #[Route('/home3', name: 'app_home3')]
    public function index3(): Response
    {
        return $this->render('home/index3.html.twig', []);
    }

    #[Route('/home4', name: 'app_home4')]
    public function index4(): Response
    {
        return $this->render('home/index4.html.twig', []);
    }

    #[Route('/statShopOnline', name: 'app_stat_shop_online')]
    public function showStat(): Response
    {
        return $this->render('home/showstat.html.twig', []);
    }
}
