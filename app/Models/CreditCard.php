<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    protected $fillable = [
        'user_id',
        'card_number',
        'card_name',
        'card_type',
        'due_date',
        'closing_date',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'credit_card_id');
    }

    public function name()
    {
        return $this->card_name . ' ' . $this->card_type . ' **** ' . substr($this->card_number, -4);
    }
}
