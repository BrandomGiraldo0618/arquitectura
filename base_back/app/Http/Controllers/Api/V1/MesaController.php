<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\MesaRequest;
use App\Models\Mesa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mesas = DB::select("SELECT A.id, A.nombre, B.nombre AS punto_nombre
                                FROM mesas AS A
                                    INNER JOIN punto_votacio AS B ON A.punto_votacio_id = B.id");

        return response()->json(
            $mesas
        , Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MesaRequest $request)
    {
        $new_mesa= new Mesa($request->all());
        $new_mesa->save();
        //echo $request->punto_votacio_id;
        return response()->json(['ok'=>true],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mesa  $mesa
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mesa= Mesa::findOrFail($id);
        return $mesa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mesa  $mesa
     * @return \Illuminate\Http\Response
     */
    public function update($id,MesaRequest $request)
    {
        $mesa= Mesa::findOrFail($id);
        $mesa->nombre = $request->get('nombre');
        $mesa->punto_votacio_id = $request->get('punto_votacio_id');

        $mesa->save();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mesa  $mesa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mesa=Mesa::findOrFail($id);
        $mesa->delete();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }

    public function consultarPorPuntoVotacion($id)
    {
        
        $mesa = DB::select("SELECT * 
                            FROM mesas 
                            WHERE punto_votacio_id =$id");
        return response()->json($mesa, Response::HTTP_OK);
    
    }
}
