<?php

namespace App\Filament\Resources\Tagihans\Pages;

use App\Filament\Resources\Tagihans\TagihanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTagihan extends CreateRecord
{
    protected static string $resource = TagihanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['total_tagihan'] = (float) str_replace([',', 'Rp', ' '], '', $data['total_tagihan']);
        $data['tagihan_terbayar'] = 0;
        $data['sisa_tagihan'] = $data['total_tagihan'] - $data['tagihan_terbayar'];
        return $data;
    }
}
