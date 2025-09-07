<?php

namespace App\Filament\Resources\Tagihans\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\Tagihans\TagihanResource;
use App\Filament\Resources\Tagihans\Widgets\TagihanOverview;

class ListTagihans extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TagihanOverview::class,
        ];
    }
}
