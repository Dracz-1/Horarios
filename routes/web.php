<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('dashboard');
})->name('home',);




Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/inicio', function () {
        return view('inicio');
        })->name('inicio');
    
    Route::get('/criarCartao', function () {
        return view('criarCartao');
        })->name('criarCartao');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/horario', function () {
    return view('horario');
    })->name('horario');   
    Route::get('/dashboard', function () {
        return view('dashboard');
        })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
