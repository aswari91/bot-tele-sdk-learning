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
        $data['total_anualfee'] = (float) str_replace([',', 'Rp', ' '], '', $data['total_anualfee']);

        return $data;
    }
}
