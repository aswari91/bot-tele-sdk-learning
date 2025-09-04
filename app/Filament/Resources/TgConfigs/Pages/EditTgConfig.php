<?php

namespace App\Filament\Resources\TgConfigs\Pages;

use App\Filament\Resources\TgConfigs\TgConfigResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTgConfig extends EditRecord
{
    protected static string $resource = TgConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            // DeleteAction::make(),
        ];
    }
}
