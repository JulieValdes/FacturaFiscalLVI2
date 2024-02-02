<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\SujetoController;
use App\Http\Controllers\VentaController;


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
    Route::get('empresas-by-user', [EmpresaController::class, 'empresasByUser'])->name('empresas.byUser');
    Route::post('setSessionEmpresa', [EmpresaController::class, 'setSessionEmpresa'])->name('empresas.setSessionEmpresa');

    /*---------------Rutas de sujetos------------ */
    Route::get('sujetos' , [SujetoController::class, 'index'])->name('sujetos.index');
    Route::post('sujetos' , [SujetoController::class, 'store'])->name('sujetos.store');
    Route::put('sujetos/{id}' , [SujetoController::class, 'update'])->name('sujetos.update');
    Route::delete('sujetos/{sujeto}/{empresa}' , [SujetoController::class, 'destroy'])->name('sujetos.destroy');

    /*---------------Rutas de articulos------------ */
    Route::get('articulos' , [ArticuloController::class, 'index'])->name('articulos.index');
    Route::post('articulos' , [ArticuloController::class, 'store'])->name('articulos.store');
    Route::put('articulos/{id}' , [ArticuloController::class, 'update'])->name('articulos.update');
    Route::delete('articulos/{articulo}/{empresa}' , [ArticuloController::class, 'destroy'])->name('articulos.destroy');

    /*---------------Rutas de ventas------------ */
    Route::get('ventas' , [VentaController::class, 'index'])->name('ventas.index');
    Route::post('ventas' , [VentaController::class, 'store'])->name('ventas.store');
    Route::put('ventas/{id}' , [VentaController::class, 'update'])->name('ventas.update');
    Route::delete('ventas/{venta}/{empresa}' , [VentaController::class, 'destroy'])->name('ventas.destroy');
});

Route::get('prueba', function ($id) {
    return "Has accedido a la ruta";
})->middleware('checkAdmin');


require __DIR__.'/auth.php';
