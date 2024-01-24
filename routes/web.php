<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\SujetoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Route::resource('empresas', EmpresaController::class);
    Route::get('empresas' , [EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('empresas/create' , [EmpresaController::class, 'create'])->name('empresas.create');
    Route::post('empresas/create' , [EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('empresas/edit/{id}' , [EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::put('empresas/edit/{id}' , [EmpresaController::class, 'update'])->name('empresas.update');
    Route::delete('empresas/{empresa}' , [EmpresaController::class, 'destroy'])->name('empresas.destroy');

    Route::get('sujetos' , [SujetoController::class, 'index'])->name('sujetos.index');
    Route::get('sujetos/create' , [SujetoController::class, 'create'])->name('sujetos.create');
    Route::post('sujetos/create' , [SujetoController::class, 'store'])->name('sujetos.store');
    Route::get('sujetos/edit/{id}' , [SujetoController::class, 'edit'])->name('sujetos.edit');
    Route::put('sujetos/edit/{id}' , [SujetoController::class, 'update'])->name('sujetos.update');
    Route::delete('sujetos/{sujeto}/{empresa}' , [SujetoController::class, 'destroy'])->name('sujetos.destroy');
});

require __DIR__.'/auth.php';
