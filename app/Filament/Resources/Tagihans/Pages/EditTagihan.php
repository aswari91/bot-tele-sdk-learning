<?php

namespace App\Filament\Resources\Tagihans\Pages;

use App\Filament\Resources\Tagihans\TagihanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTagihan extends EditRecord
{
    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total_tagihan'] = (float) str_replace([',', 'Rp', ' '], '', $data['total_tagihan']);
        $data['tagihan_terbayar'] = 0;
        $data['sisa_tagihan'] = $data['total_tagihan'] - $data['tagihan_terbayar'];
        return $data;
    }
}
