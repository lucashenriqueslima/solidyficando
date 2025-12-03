<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/doacoes/criar', App\Livewire\CreateDonation::class)->name('donations.create');
