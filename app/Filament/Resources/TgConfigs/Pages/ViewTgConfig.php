<?php

namespace App\Filament\Resources\TgConfigs\Pages;

use App\Filament\Resources\TgConfigs\TgConfigResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTgConfig extends ViewRecord
{
    protected static string $resource = TgConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
