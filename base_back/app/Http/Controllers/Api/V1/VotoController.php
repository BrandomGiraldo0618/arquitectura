<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Voto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Voto::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Que el votante exitasta como votante

        $cedula =  $request->input('numero_identificacion');
        $persona = Persona::where('numero_Documento', $cedula);

        if(null != $persona){
            //Que no tenga un voto registrado de ese tipo (senado - camara)
            $voto = Voto::where('votante_id', $persona->id)
                        ->where('tipo_id', $request->tipo_id);

            if(null != $voto){
                //REGISTRAMOS EL VOTO
            }else{
                //RETORNA ERROR
            }
        }



        $new_voto = new Voto();
        $new_voto->fecha=$request->fecha;
        $new_voto->votante_id = $request->votante_id;
        $new_voto->tipo_id = $request->tipo_id;
        $new_voto->partido_id = $request->partido_id;

        $new_voto->save();

        return response()->json(['ok'=>true],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
