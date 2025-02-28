<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetalleCompraController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedoreController;
use App\Http\Controllers\VentaController;
use App\Models\Detalle_Venta;
use Illuminate\Support\Facades\Route;


// Rutas públicas (no requieren autenticación)
Route::view('/login', 'auth.login')->name('login');
Route::view('/registro', 'auth.register')->name('registro');

// Rutas de autenticación
Route::post('/validar-registro',[LoginController::class, 'register'])->name('validar-registro');
Route::post('/inicia-sesion',[LoginController::class, 'login'])->name('inicia-sesion');

// Todas las rutas protegidas con middleware 'auth'
Route::middleware(['auth'])->group(function() {
    // Ruta del dashboard
    Route::get('/', [DashboardController::class, 'dashboard'])->name('home');
    
    // Ruta de logout (debe estar protegida)
    Route::get('/logout',[LoginController::class, 'logout'])->name('logout');

    // Rutas de marcas
    Route::controller(MarcaController::class)->group(function(){
        Route::get('/marcas','index')->name('marcas');
        Route::get('/marcas/list', 'DevolverMarcas');
        Route::post('/marcas/list', 'store'); 
        Route::put('/marcas/list/{id}', 'update'); 
        Route::delete('/marcas/list/{id}', 'destroy');
    });

    // Rutas de categorías
    Route::controller(CategoriaController::class)->group(function(){
        Route::get('/categorias','index')->name('categorias');
        Route::get('/categorias/list', 'Fletch');
        Route::post('/categorias/list', 'store'); 
        Route::put('/categorias/list/{id}', 'update'); 
        Route::delete('/categorias/list/{id}', 'destroy');
    });

    // Rutas de proveedores
    Route::controller(ProveedoreController::class)->group(function(){
        Route::get('/proveedores','index')->name('proveedores'); 
        Route::get('/proveedores/list', 'flecht');
        Route::post('/proveedores/list', 'store'); 
        Route::put('/proveedores/list/{id}', 'update');
        Route::delete('/proveedores/list/{id}', 'destroy');
    });

    // Rutas de productos
    Route::controller(ProductoController::class)->group(function(){
        Route::get('/productos','index')->name('productos'); 
        Route::get('/productos/list', 'registro');
        Route::post('/productos/list', 'store'); 
        Route::put('/productos/list/{id}', 'update');
        Route::delete('/productos/list/{id}', 'destroy');
    });

    // Rutas de compras
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

    // Rutas de ventas
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
});