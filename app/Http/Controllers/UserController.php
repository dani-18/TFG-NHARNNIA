<?php

namespace App\Http\Controllers;

use App\Models\User; // Importa el modelo de usuario predeterminado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index (){
        $user = Auth::user();
        $usuarios = User::all();
        return view('usuario.index', compact('usuarios'));
    }

    /**
     * Registro de nuevos usuarios.
     */
    public function registro(Request $request)
    {
        // Validar los datos del formulario de registro
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear un nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Iniciar sesión después del registro
        Auth::login($user);

        // Redirigir a la página deseada después del registro
        return redirect()->route('prendas.index');
    }

    /**
     * Inicio de sesión de usuarios existentes.
     */
    public function inicioSesion(Request $request)
    {
        // Validar los datos del formulario de inicio de sesión
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($request->only('email', 'password'))) {
            // Autenticación exitosa
            return redirect()->route('prendas.index');
        }

        // Autenticación fallida
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas son incorrectas.',
        ]);
    }

    /**
     * Cerrar sesión de usuarios autenticados.
     */
    public function cerrarSesion()
    {
        Auth::logout();
        return redirect('/formulario-inicio-sesion');
    }

    public function mostrarFormularioRegistro(){
        return view('sesion.formulario-registro');
    }

    public function mostrarFormularioInicioSesion(){
        return view('sesion.formulario-inicio-sesion');
    }

    /**
     * Eliminar un usuario existente.
     */
    public function eliminarUsuario(User $user)
    {
        // Verificar si el usuario está autenticado y tiene permiso para eliminar usuarios
        if (Auth::check() && Auth::user()->id === $user->id) {
            // No permitir que un usuario se elimine a sí mismo
            return back()->withErrors([
                'error' => 'No puedes eliminar tu propia cuenta.',
            ]);
        }

        // Eliminar el usuario
        $user->delete();

        // Redirigir a la página deseada después de eliminar el usuario
        return redirect()->route('usuario.index');
    }

    /**
     * Mostrar formulario para modificar un usuario existente.
     */
    public function mostrarFormularioModificar(User $user)
    {
        // Verificar si el usuario está autenticado y tiene permiso para modificar usuarios
        if (Auth::check()) {
            // Obtener todos los usuarios
            $usuarios = User::all();
            
            // Mostrar el formulario de modificación
            return view('usuario.modificar', compact('user', 'usuarios'));
        } else {
            // Redirigir si el usuario no tiene permiso para modificar
            return back()->withErrors([
                'error' => 'No tienes permiso para modificar este usuario.',
            ]);
        }
    }

    /**
     * Modificar un usuario existente.
     */
    public function modificarUsuario(Request $request, User $user)
    {
        // Validar los datos del formulario de modificación
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            // Se asegura de que el email sea único, excluyendo el email del usuario actual
        ]);

        // Actualizar los datos del usuario
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Redirigir a la página deseada después de modificar el usuario
        return redirect()->route('usuario.index');
    }

}
