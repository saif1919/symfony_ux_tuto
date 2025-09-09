<?php

namespace App\Twig\Components;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class StatShopOnline
{
    #[LiveProp(writable: true)]
    public array $selectedMonths = [];

    #[LiveProp(writable: false)]
    public array $months = [];

    use DefaultActionTrait;

    public function __construct(private ChartBuilderInterface $chartBuilder) {}

    public function mount(): void
    {
        $brutData = [
            ['month' => 'Janvier',   'sales' => 120, 'visits' => 800],
            ['month' => 'Février',   'sales' => 150, 'visits' => 950],
            ['month' => 'Mars',      'sales' => 200, 'visits' => 1100],
            ['month' => 'Avril',     'sales' => 250, 'visits' => 1200],
            ['month' => 'Mai',       'sales' => 300, 'visits' => 1400],
            ['month' => 'Juin',      'sales' => 280, 'visits' => 1350],
            ['month' => 'Juillet',   'sales' => 400, 'visits' => 1600],
            ['month' => 'Août',      'sales' => 380, 'visits' => 1550],
            ['month' => 'Septembre', 'sales' => 420, 'visits' => 1700],
            ['month' => 'Octobre',   'sales' => 460, 'visits' => 1800],
            ['month' => 'Novembre',  'sales' => 500, 'visits' => 2000],
            ['month' => 'Décembre',  'sales' => 550, 'visits' => 2200],
        ];
        $this->months =  array_column($brutData, 'month');
    }

    #[ExposeInTemplate()]
    public function showStatShopOnline()
    {
        $data = $this->filterData($this->selectedMonths);
        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $data['months'],
            'datasets' => [
                [
                    'type' => 'bar',
                    'label' => 'Ventes',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => $data['sales'],
                ],
                [
                    'type' => 'bar',
                    'label' => 'Visites',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.3)',
                    'fill' => false,
                    'tension' => 0.4,
                    'data' => $data['visites'],
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => ['position' => 'top'],
                'title' => [
                    'display' => true,
                    'text' => 'Ventes & Visites par mois',
                ],
            ],
            'scales' => [
                'y' => ['beginAtZero' => true],
            ],
        ]);
        return $chart;
    }

    // #[ExposeInTemplate()]
    // public function months()
    // {
    //     dd('test');
    //     $brutData = [
    //         ['month' => 'Janvier',   'sales' => 120, 'visits' => 800],
    //         ['month' => 'Février',   'sales' => 150, 'visits' => 950],
    //         ['month' => 'Mars',      'sales' => 200, 'visits' => 1100],
    //         ['month' => 'Avril',     'sales' => 250, 'visits' => 1200],
    //         ['month' => 'Mai',       'sales' => 300, 'visits' => 1400],
    //         ['month' => 'Juin',      'sales' => 280, 'visits' => 1350],
    //         ['month' => 'Juillet',   'sales' => 400, 'visits' => 1600],
    //         ['month' => 'Août',      'sales' => 380, 'visits' => 1550],
    //         ['month' => 'Septembre', 'sales' => 420, 'visits' => 1700],
    //         ['month' => 'Octobre',   'sales' => 460, 'visits' => 1800],
    //         ['month' => 'Novembre',  'sales' => 500, 'visits' => 2000],
    //         ['month' => 'Décembre',  'sales' => 550, 'visits' => 2200],
    //     ];
    //     return array_column($brutData, 'month');
    // }

    public function filterData(array $selectedMonths)
    {
        $data = [
            ['month' => 'Janvier',   'sales' => 120, 'visits' => 800],
            ['month' => 'Février',   'sales' => 150, 'visits' => 950],
            ['month' => 'Mars',      'sales' => 200, 'visits' => 1100],
            ['month' => 'Avril',     'sales' => 250, 'visits' => 1200],
            ['month' => 'Mai',       'sales' => 300, 'visits' => 1400],
            ['month' => 'Juin',      'sales' => 280, 'visits' => 1350],
            ['month' => 'Juillet',   'sales' => 400, 'visits' => 1600],
            ['month' => 'Août',      'sales' => 380, 'visits' => 1550],
            ['month' => 'Septembre', 'sales' => 420, 'visits' => 1700],
            ['month' => 'Octobre',   'sales' => 460, 'visits' => 1800],
            ['month' => 'Novembre',  'sales' => 500, 'visits' => 2000],
            ['month' => 'Décembre',  'sales' => 550, 'visits' => 2200],

        ];

        if (empty($selectedMonths)) {
            $filtered = $data;
        } else {
            $filtered = array_filter($data, function ($item) use ($selectedMonths) {
                return in_array($item['month'], $selectedMonths);
            });
        }
        return [
            'months' => array_column($filtered, 'month'),
            'sales' =>  array_column($filtered, 'sales'),
            'visites' =>  array_column($filtered, 'visits')
        ];
    }
}
