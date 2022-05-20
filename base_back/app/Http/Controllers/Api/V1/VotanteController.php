<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\PersonaRequest;
use App\Http\Requests\VotanteRequest;
use App\Models\Persona;
use App\Models\Votante;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VotanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Votante::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(VotanteRequest $request)
    {
        $new_votante= new Votante();
        $new_votante->huella = $request->huella;
        $new_votante->persona_id = $request->persona_id;
        $new_votante->mesa_id = $request->mesa_id;
        $new_votante->save();
        return response()->json(['ok'=>true],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Votante  $votante
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $votante= Votante::findOrFail($id);
        return $votante;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Votante  $votante
     * @return \Illuminate\Http\Response
     */
    public function update($id,VotanteRequest  $request)
    {
        $votante= Votante::findOrFail($id);
        $votante->huella = $request->get('huella');
        $votante->persona_id = $request->get('persona_id');
        $votante->mesa_id = $request->get('mesa_id');

        $votante->save();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Votante  $votante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $votante=Votante::findOrFail($id);
        $votante->delete();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }
}
