<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedoreController;
use Illuminate\Support\Facades\Route;
/*
Route::get('/', function () {
    return view('dashboard')->name('home');
});*/

Route::get('/', [DashboardController::class, 'dashboard'])->name('home');


Route::controller(MarcaController::class)->group(function(){
    Route::get('/marcas','index')->name('marcas'); ; // muestra la vista
    Route::get('/marcas/list', 'DevolverMarcas'); // llena las marcas
    Route::post('/marcas/list', 'store'); 
    Route::put('/marcas/list/{id}', 'update'); 
    Route::delete('/marcas/list/{id}', 'destroy');
});

Route::controller(CategoriaController::class)->group(function(){
    Route::get('/categorias','index')->name('categorias'); ; // muestra la vista
    Route::get('/categorias/list', 'Fletch'); // llena las marcas
    Route::post('/categorias/list', 'store'); 
    Route::put('/categorias/list/{id}', 'update'); 
    Route::delete('/categorias/list/{id}', 'destroy');
});

//proveedores rutas
Route::controller(ProveedoreController::class)->group(function(){
    Route::get('/proveedores','index')->name('proveedores'); 
    Route::get('/proveedores/list', 'flecht');
    Route::post('/proveedores/list', 'store'); 
    Route::put('/proveedores/list/{id}', 'update');
    Route::delete('/proveedores/list/{id}', 'destroy');
});

//Productos rutas
Route::controller(ProductoController::class)->group(function(){
    Route::get('/productos','index')->name('productos'); 
    Route::get('/productos/list', 'registro');
    Route::post('/productos/list', 'store'); 
    Route::put('/productos/list/{id}', 'update');
    Route::delete('/productos/list/{id}', 'destroy');
});