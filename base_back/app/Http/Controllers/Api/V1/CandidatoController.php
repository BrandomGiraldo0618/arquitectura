<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CandidatoRequest;
use App\Models\Candidato;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CandidatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Candidato::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(CandidatoRequest $request)
    {
        $new_candidato= new Candidato();
        $new_candidato->persona_id = $request->persona_id;
        $new_candidato->numero_inscripcion = $request->numero_inscripcion;
        $new_candidato->tipo_id = $request->tipo_id;
        $new_candidato->partido_id = $request->partido_id;
        $new_candidato->save();
        return response()->json(['ok'=>true],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidato  $candidato
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $votante= Candidato::findOrFail($id);
        return $votante;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidato  $candidato
     * @return \Illuminate\Http\Response
     */
    public function update($candidato, Request $request  )
    {
        $new_candidato= Candidato::findOrFail($candidato);
        $new_candidato->persona_id = $request->get('persona_id');
        $new_candidato->numero_inscripcion = $request->get('numero_inscripcion');
        $new_candidato->tipo_id = $request->get('tipo_id');
        $new_candidato->partido_id = $request->get('partido_id');
        $new_candidato->save();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidato  $candidato
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $votante=Candidato::findOrFail($id);
        $votante->delete();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }
}
