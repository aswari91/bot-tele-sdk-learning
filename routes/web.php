<?php

use App\Livewire\BayarTagihan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('/admin');
});

Route::get('/bayar-tagihan', BayarTagihan::class)->name('bayar-tagihan');
