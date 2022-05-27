<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\PuntoVotacioRequest;
use App\Models\PuntoVotacio;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PuntoVotacioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PuntoVotacio::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PuntoVotacioRequest $request)
    {
        $new_punto= new PuntoVotacio($request->all());
        $new_punto->save();
        return response()->json(['ok'=>true],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PuntoVotacio  $PuntoVotacio
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $punto= PuntoVotacio::findOrFail($id);
        return $punto;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PuntoVotacio  $PuntoVotacio
     * @return \Illuminate\Http\Response
     */
    public function update($id, PuntoVotacioRequest $request)
    {
        $punto= PuntoVotacio::findOrFail($id);
        $punto->nombre = $request->get('nombre');
        $punto->direccion = $request->get('direccion');

        $punto->save();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PuntoVotacio  $puntoVotacio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $punto=PuntoVotacio::findOrFail($id);
        $punto->delete();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }
}
