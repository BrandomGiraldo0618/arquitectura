<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Voto;
use App\Models\Partido;
use App\Models\Candidato;
use App\Models\Votante;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        $persona = Persona::where('numero_Documento', $cedula)->first();
        $votante= Votante::where('persona_id',$persona->id)->first();
        if(null != $votante){
            //Que no tenga un voto registrado de ese tipo (senado - camara)
            $voto = DB::select("SELECT id 
                                    FROM votos 
                                WHERE votante_id = $votante->id AND tipo_id = $request->tipo_voto");
            
            if(null == $voto){
                //REGISTRAMOS EL VOTO
                $new_voto = new Voto();
                $new_voto->fecha = now();
                $new_voto->votante_id = $votante->id;
                $new_voto->tipo_id = $request->tipo_voto;
                $new_voto->partido_id = $request->partido_id;

                if("" == $request->candidato_id){
                    $new_voto->candidato_id = null; 
                }else{
                    $new_voto->candidato_id = $request->candidato_id; 
                }

                $new_voto->save();
                $reponse=["ok"=>true,"mensaje"=>"Voto registrado exitosamente"];
            }else{
                $reponse=["ok"=>false,"mensaje"=>"Ya existe un voto de este tipo"];
            }
        }else{
            $reponse=["ok"=>false,"mensaje"=>"Usted no esta registrado como votante"];

        }

        return response()->json($reponse,Response::HTTP_CREATED);
    }

    public function validarVotante($documento){

        $persona = Persona::where('numero_Documento', $documento)->first();

        if(null != $persona){
            $votante= Votante::where('persona_id',$persona->id)->first();
            
            if(null != $votante){
                return response()->json(true,Response::HTTP_OK);
            }else{
                return response()->json(false,Response::HTTP_OK);
            }
        }else{
            return response()->json(false,Response::HTTP_OK);
        }
        
    }

    public function partidoListaAC($partido_id, $tipo_voto){

        $partido = Partido::find($partido_id);

        if($partido->listaA_C==true){

            $candidatos = DB::Select("SELECT *
                                        FROM Candidatos c
                                        INNER JOIN Personas p
                                            ON c.persona_id = p.id
                                        WHERE c.partido_id =  $partido_id
                                        AND c.tipo_id = $tipo_voto
                                    ");
            //$candidatos=Candidato::where('partido_id', $partido_id)->get();
        }else{
            $candidatos=[];
        }
        return response()->json($candidatos,Response::HTTP_CREATED);
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
