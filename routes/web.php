<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetalleCompraController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedoreController;
use App\Http\Controllers\VentaController;
use App\Models\Detalle_Venta;
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

//Compra rutas
Route::controller(CompraController::class)->group(function(){
    Route::get('/compras','index')->name('compras');
    Route::get('/compras/list','flecht');
    Route::post('/compras','store');             
    Route::put('/compras/{id}','update');        
    Route::delete('/compras/{id}','destroy');    
});

Route::controller(DetalleCompraController::class)->group(function(){
    Route::post('/detalle-compras','store');    
});

Route::get('/compras/reporte/{id}', [CompraController::class, 'reporteCompra']);
Route::get('/ventas/boleta/{id}', [VentaController::class, 'boleta'])->name('ventas.boleta');
Route::get('/reporte-diario', [VentaController::class, 'ventasDelDia'])->name('reporte.diario');


Route::controller(VentaController::class)->group(function(){
    Route::get('/ventas','index')->name('ventas');
    Route::get('/ventas/list','flecht');
    Route::post('/ventas/list','store')->name('Detalle');
    Route::post('/ventas/{id}/finalizar', 'finalizarVenta');   
});

Route::controller(DetalleVentaController::class)->group(function(){
    Route::get('/NuevaVenta/{id?}', 'index')->name('boleta');
    Route::get('/NuevaVenta/list/{id?}', 'flecht');
    Route::post('/NuevaVenta/detalle', 'store')->name('store.detalle');
    Route::delete('/NuevaVenta/detalle/{id}', 'destroy');
});
