<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prenda;
use Illuminate\Support\Facades\Auth;

class PrendaController extends Controller
{

    public function inicio(){
        return view('inicio.index');
    }

    public function index()
    {
        $user = Auth::user();
        $prendas = Prenda::all();
        return view('prendas.index', compact('prendas','user'));
    }

    public function indexHombre()
    {
        $user = Auth::user();
        $prendas = Prenda::where('genero', 'hombre')->get();
        return view('prendas.index', compact('prendas', 'user'));
    }

    public function indexMujer()
    {
        $user = Auth::user();
        $prendas = Prenda::where('genero', 'mujer')->get();
        return view('prendas.index', compact('prendas', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'estilo' => 'required',
            'color1' => 'required',
            'color2' => 'required',
            'imagen' => 'required',
            'tipo' => 'required',
            'precio' => 'required',
            'genero' => 'required',
          ]);
          Prenda::create($request->all());
          return redirect()->route('prendas.index')
            ->with('success','Prenda created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $prenda = Prenda::find($id);
        return view('prendas.show', compact('prenda'));
    }

    public function create()
    {
        return view('prendas.create');
    }

}
