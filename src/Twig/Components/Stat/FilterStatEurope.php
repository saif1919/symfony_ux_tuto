<?php

namespace App\Twig\Components\Stat;

use App\Provider\RestcountriesProvider;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class FilterStatEurope
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: false)]
    public ?array $subregion = [];

    #[LiveProp(writable: true)]
    public ?string $selectedSubregion = null;

        public function __construct(
        private RestcountriesProvider $restcountriesProvider
    ) {}

        public function mount(): void
    {
        $this->subregion = array_unique(array_column($this->restcountriesProvider->getEuropeStat(), 'subregion'));
        sort($this->subregion);
    }

    #[LiveAction]
    public function emitSelectedSubregion()
    {
        $this->emit('selectedSubregionEvent',[
            'subregion' => $this->selectedSubregion
        ]);
    }
}
