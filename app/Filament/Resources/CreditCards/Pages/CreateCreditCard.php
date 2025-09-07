<?php

namespace App\Filament\Resources\CreditCards\Pages;

use App\Filament\Resources\CreditCards\CreditCardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCreditCard extends CreateRecord
{
    protected static string $resource = CreditCardResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
