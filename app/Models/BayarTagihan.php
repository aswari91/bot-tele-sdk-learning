<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BayarTagihan extends Model
{
    protected $fillable = [
        'user_id',
        'tagihan_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'metode_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'float',
            'tanggal_bayar' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
}
