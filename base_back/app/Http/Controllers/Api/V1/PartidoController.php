<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Partido;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\PartidoRequest;

class PartidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Partido::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartidoRequest $request)
    {
        $new_partido = new Partido();
        $new_partido->nombre=$request->nombre;
        $new_partido->personaid_rlegal=$request->personaid_rlegal;
        $new_partido->listaa_c=$request->listaa_c;
        $new_partido->save();
        return response()->json(['ok'=>true],Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partido  $partido
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $votante= Partido::findOrFail($id);
        return $votante;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partido  $partido
     * @return \Illuminate\Http\Response
     */
    public function update($partido, Request $request  )
    {
        $new_partido = Partido::findOrFail($partido);
        $new_partido->nombre = $request->get('nombre');
        $new_partido->personaid_rlegal = $request->get('personaid_rlegal');
        $new_partido->listaa_c = $request->get('listaa_c');   
        $new_partido->save();
        return response()->json(['ok'=>true],Response::HTTP_CREATED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partido  $partido
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partido=Partido::findOrFail($id);
        $partido->delete();
        return response()->json(['ok'=>true],Response::HTTP_OK);

    }
}
