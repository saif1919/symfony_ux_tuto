<?php

namespace App\Twig\Components\Stat;

use App\Provider\RestcountriesProvider;
use Pentiminax\UX\DataTables\Builder\DataTableBuilderInterface;
use Pentiminax\UX\DataTables\Model\Column;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class StatEurope2
{
    use DefaultActionTrait;

    #[LiveProp(writable: false)]
    public ?array $subregion = [];

    #[LiveProp(writable: true)]
    public ?string $selectedSubregion = null;

    public function __construct(
        private RestcountriesProvider $restcountriesProvider,
        private ChartBuilderInterface $chartBuilder,
        private DataTableBuilderInterface $dataTableBuilder
    ) {}

    public function mount(): void
    {
        $this->subregion = array_unique(array_column($this->restcountriesProvider->getEuropeStat(), 'subregion'));
        sort($this->subregion);
    }



    #[ExposeInTemplate()]
    public function chartStatEurope()
    {
        $data = $this->restcountriesProvider->getEuropeStat($this->selectedSubregion);

        if (!is_null($this->selectedSubregion) && $this->selectedSubregion !== '') {
            $chart = $this->chartCountries($data);
        } else {
            $chart = $this->chartRegion($data);
        }
        return $chart;
    }

    private function chartRegion($data)
    {
        $regionData = [];
        foreach ($data as $country) {
            $subregion = $country['subregion'] ?? 'Unknown';
            $regionData[$subregion] = ($regionData[$subregion] ?? 0) + $country['population'];
        }

        // $labels = noms des sous-régions
        $labels = array_keys($regionData);

        // $populations = population totale par sous-région
        $populations = array_values($regionData);
        $chart = $this->chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $populations,
                    'backgroundColor' =>  $this->generateColors(count($labels)),
                    'borderColor' => 'rgba(255, 255, 255, 1)',
                    'borderWidth' => 1,
                ]
            ]
        ]);
        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false, // 🔑 indispensable
            'plugins' => [
                'legend' => ['position' => 'top'],
                'title' => [
                    'display' => true,
                    'text' => 'Population en Europe',
                ],
            ],
        ]);

        return $chart;
    }

    private function chartCountries($data)
    {
        $labels = array_map(fn($c) => $c['name']['common'], $data);
        $populations = array_map(fn($c) => $c['population'], $data);
        $chart = $this->chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $populations,
                    'backgroundColor' =>  $this->generateColors(count($labels)),
                    'borderColor' => 'rgba(255, 255, 255, 1)',
                    'borderWidth' => 1,
                ]
            ]
        ]);
        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => ['position' => 'top'],
                'title' => [
                    'display' => true,
                    'text' => 'Population en Europe',
                ],
            ],
        ]);

        return $chart;
    }


    private function generateColors(int $count): array
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $hue = ($i * 360 / $count); // répartit les couleurs sur le cercle
            $colors[] = "hsl($hue, 70%, 50%)";
        }
        return $colors;
    }

    #[ExposeInTemplate()]
    public function datatablesStatEurope()
    {

        $data = $this->restcountriesProvider->getEuropeStat($this->selectedSubregion);
        $result = array_map(function ($country) {
            return [
                $country['name']['common'] ?? 'N/A',
                $country['subregion'] ?? 'N/A',
                $country['capital'][0] ?? 'N/A',
                number_format($country['population'] ?? 0, 0, ',', ' '),
            ];
        }, $data);

        $table = $this->dataTableBuilder
            ->createDataTable('countriesDataTable')
            ->columns([
                Column::new('nom', 'nom'),
                Column::new('subregion', 'Région'),
                Column::new('capital', 'Capitale'),
                Column::new('population', 'Population'),
            ])
            ->data($result);

        return $table;
    }
}
