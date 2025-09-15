<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Motivo;
use App\Models\Question;



class GameController extends Controller
{
    public function controlPanel()
{
    $motivos = Motivo::with('categorias')->get();
    $categorias = Categoria::all();
    return view('game', compact('motivos', 'categorias'));
}

public function storeMotivo(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:100|unique:motivos,nombre',
    ]);
    Motivo::create(['nombre' => $request->nombre]);
    return back()->with('success', 'Motivo creado correctamente.');
}

public function storeCategoria(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:100',
        'motivo_id' => 'required|exists:motivos,id',
    ]);
    Categoria::create([
        'nombre' => $request->nombre,
        'motivo_id' => $request->motivo_id
    ]);
    return back()->with('success', 'Categoría creada correctamente.');
}

public function storePregunta(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categorias,id',
        'texto' => 'required|string|max:255',
        'opcion_correcta' => 'required|string|max:255',
        'opcion_1' => 'required|string|max:255',
        'opcion_2' => 'required|string|max:255',
        'opcion_3' => 'required|string|max:255',
    ]);
    Question::create([
        'category_id' => $request->category_id,
        'texto' => $request->texto,
        'opcion_correcta' => $request->opcion_correcta,
        'opcion_1' => $request->opcion_1,
        'opcion_2' => $request->opcion_2,
        'opcion_3' => $request->opcion_3,
        'correct_index' => 0,
    ]);
    return back()->with('success', 'Pregunta creada correctamente.');
}

}
