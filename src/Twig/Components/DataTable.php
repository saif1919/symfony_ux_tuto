<?php

namespace App\Twig\Components;

use Pentiminax\UX\DataTables\Builder\DataTableBuilderInterface;
use Pentiminax\UX\DataTables\Enum\ButtonType;
use Pentiminax\UX\DataTables\Enum\Feature;
use Pentiminax\UX\DataTables\Model\Column;
use Pentiminax\UX\DataTables\Model\Extensions\ButtonsExtension;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
final class DataTable
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $name = null;

    public function __construct(private DataTableBuilderInterface $builder) {}

    #[ExposeInTemplate()]
    public function getDataTable()
    {
        $data = [
             ['John', 'Doe'],
                ['Jane', 'Smith'],
                ['Saif', 'Naimi']
        ];
        if($this->name !== null && $this->name !== '' ){
            $data = array_filter($data, function($row) {
                return stripos($row[0], $this->name) !== false;
            });

            $data = array_values($data);
        }
        $buttonsExtension = new ButtonsExtension([
    ButtonType::COPY,
    ButtonType::CSV,
    ButtonType::EXCEL,
    ButtonType::PDF,
    ButtonType::PRINT
]);

        $table = $this->builder
            ->createDataTable('usersTable')
            ->columns([
                Column::new('firstName', 'First name'),
                Column::new('lastName', 'Last name'),
            ])
            ->data($data);
        $table->extensions([$buttonsExtension]);
        $table->layout(Feature::BUTTONS);
        return $table;
    }
}
