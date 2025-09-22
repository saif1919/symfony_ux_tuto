<?php

namespace App\Twig\Components\Stat;

use App\Provider\RestcountriesProvider;
use Pentiminax\UX\DataTables\Builder\DataTableBuilderInterface;
use Pentiminax\UX\DataTables\Model\Column;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class DataTableStatEurope
{
    public ?string $selectedSubregion = null;
    use DefaultActionTrait;

    public function __construct(
        private RestcountriesProvider $restcountriesProvider,
        private DataTableBuilderInterface $dataTableBuilder
    ) {}

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

        return $this->dataTableBuilder
            ->createDataTable('countriesDataTable')
            ->columns([
                Column::new('nom', 'nom'),
                Column::new('subregion', 'Région'),
                Column::new('capital', 'Capitale'),
                Column::new('population', 'Population'),
            ])
            ->data($result);
    }
    #[LiveListener('selectedSubregionEvent')]
    public function getSelectSubregion(#[LiveArg()] ?string $subregion)
    {
        $this->selectedSubregion = $subregion;
    }
}
