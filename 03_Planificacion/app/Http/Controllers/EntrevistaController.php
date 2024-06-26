<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntrevista;
use App\Models\Entrevista;
use App\Models\Cliente;
use Illuminate\Http\Request;

/**
 * Class EntrevistaController
 * @package App\Http\Controllers
 */
class EntrevistaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Obtener el término de búsqueda ingresado por el usuario
        $searchTerm = $request->input('search');

        // Cargar entrevistas
        $entrevistas = Entrevista::where('registroDatos', 'like', "%$searchTerm%") // Filtrar por registro de datos
            ->orWhere('id', 'like', "%$searchTerm%")  // Filtrar por ID de entrevista
            ->paginate(10);

        // Pasar las entrevistas a la vista
        return view('entrevista.index', compact('entrevistas'))
            ->with('i', (request()->input('page', 1) - 1) * $entrevistas->perPage());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        $entrevista = new Entrevista();
        return view('entrevista.create', compact('entrevista','clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEntrevista $request)
    {
        // request()->validate(Entrevista::$rules);
        // $entrevista = Entrevista::create($request->all());
        $entrevista = new Entrevista();
        $entrevista->registroDatos = $request->registroDatos;
        $entrevista->fecha  = $request->fecha;
        $entrevista->cliente_id = $request->cliente_id;
        $entrevista->save();

        // Asociar los cientes seleccionados
        // $entrevista->clientes()->sync($request->clientes);

        return redirect()->route('entrevistas.index')
            ->with('success', 'Entrevista created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entrevista = Entrevista::find($id);

        return view('entrevista.show', compact('entrevista'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $entrevista = Entrevista::find($id);
        $clientes = Cliente::all(); // Obtenemos todos los clientes
        return view('entrevista.edit', compact('entrevista','clientes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Entrevista $entrevista
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entrevista $entrevista)
    {
        // request()->validate(Entrevista::$rules);
        // $entrevista->update($request->all());
        $entrevista->registroDatos = $request->registroDatos;
        $entrevista->fecha  = $request->fecha;
        $entrevista->cliente_id = $request->cliente_id;
        $entrevista->save();
        return redirect()->route('entrevistas.index')
            ->with('success', 'Entrevista updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $entrevista = Entrevista::find($id)->delete();

        return redirect()->route('entrevistas.index')
            ->with('success', 'Entrevista deleted successfully');
    }
}
