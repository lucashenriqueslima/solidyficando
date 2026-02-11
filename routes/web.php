<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/QuemSomos', function () {
    return view('app');
});

Route::get('/Eventos', function () {
    return view('app');
});



Route::get('/', function () {
    return view('app');
});

Route::get('/doacoes/criar', App\Livewire\CreateDonation::class)->name('donations.create');
