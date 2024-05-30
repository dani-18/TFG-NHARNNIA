<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrendaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarritoController;



//Inicio sesion
Route::post('/registro', [UserController::class, 'registro'])->name('registro');
Route::post('/inicio-sesion', [UserController::class, 'inicioSesion'])->name('inicio-sesion');
Route::post('/cerrar-sesion', [UserController::class, 'cerrarSesion'])->name('cerrar-sesion');

Route::get('/formulario-registro', [UserController::class, 'mostrarFormularioRegistro'])->name('sesion.formulario-registro');
Route::get('/formulario-inicio-sesion', [UserController::class, 'mostrarFormularioInicioSesion'])->name('sesion.formulario-inicio-sesion');
//Fin inicio sesion

//Inicio
Route::get('/', [PrendaController::class, 'inicio'])->name('inicio.index');
//Fin inicio


Route::group(['middleware' => 'App\Http\Middleware\AuthMiddleware'], function () {

    // Prendas
    Route::get('/index', PrendaController::class .'@index')->name('prendas.index');

    Route::get('/prendas/{prenda}', PrendaController::class .'@show')->name('prendas.show');

    Route::delete('/prendas/{prenda}', PrendaController::class .'@destroy')->name('prendas.destroy');
    
    Route::post('/prendas', PrendaController::class .'@store')->name('prendas.store');
    
    Route::get('/prendas/{prenda}', PrendaController::class .'@show')->name('prendas.show');

    Route::get('/indexHombre', [PrendaController::class, 'indexHombre'])->name('prendas.indexHombre');

    Route::get('/indexMujer', [PrendaController::class, 'indexMujer'])->name('prendas.indexMujer');
    //Fin Prendas
    
    //Carrito
    Route::get('/carrito', [CarritoController::class, 'mostrarCarrito'])->name('carrito.mostrar');
    Route::post('/carrito/agregar/{idPrenda}', [CarritoController::class, 'agregarPrenda'])->name('carrito.agregar');
    Route::delete('/carrito/eliminar/{idPrenda}', [CarritoController::class, 'eliminarPrenda'])->name('carrito.eliminar');
    Route::post('/carrito/comprar', [CarritoController::class, 'comprar'])->name('carrito.comprar');
    //Fin carrito

    //Armario
    Route::get('/armario', [CarritoController::class, 'mostrarArmario'])->name('armario.mostrar');
    Route::delete('/armario/{prenda}', [CarritoController::class, 'eliminar'])->name('armario.eliminar');
    //Fin Armario

    //Outfit
    Route::get('/armario/generar-outfit', [CarritoController::class, 'mostrarFormularioGenerarOutfit'])->name('armario.generar_outfit');
    Route::post('/outfit', [CarritoController::class, 'generarOutfitCompleto'])->name('armario.outfit');
    Route::get('/outfit/lista', [CarritoController::class, 'mostrarOutfits'])->name('armario.mostrar_todos_outfits');
    Route::delete('/outfits/{outfit}', [CarritoController::class, 'destroy'])->name('outfit.destroy');
    //Fin Outfit
});


Route::group(['middleware' =>'App\Http\Middleware\AdminMiddleware'], function () {
    // Rutas protegidas para administradores
    Route::get('/prendascrear', [PrendaController::class, 'create'])->name('prendas.create');

    
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuario.index');
    // Ruta para eliminar un usuario
    Route::delete('/usuarios/{user}', [UserController::class, 'eliminarUsuario'])->name('usuario.eliminar');

    // Ruta para mostrar el formulario de modificar un usuario
    Route::get('/usuarios/{user}/editar', [UserController::class, 'mostrarFormularioModificar'])->name('usuario.mostrarFormularioModificar');

    // Ruta para modificar un usuario
    Route::put('/usuarios/{user}', [UserController::class, 'modificarUsuario'])->name('usuarios.modificar');
});
