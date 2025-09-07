<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'credit_card_id',
        'user_id',
        'total_tagihan',
        'tagihan_terbayar',
        'sisa_tagihan',
        'jatuh_tempo',
        'lunas',
    ];

    protected function casts(): array
    {
        return [
            'lunas' => 'boolean',
            'jatuh_tempo' => 'date',
            'total_tagihan' => 'float',
            // 'tagihan_terbayar' => 'decimal:2',
            // 'sisa_tagihan' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creditCard()
    {
        return $this->belongsTo(CreditCard::class, 'credit_card_id');
    }

    public function bayarTagihans()
    {
        return $this->hasMany(BayarTagihan::class, 'tagihan_id');
    }
}
