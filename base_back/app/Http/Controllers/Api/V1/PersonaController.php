<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\CandidatoRequest;
use App\Http\Requests\JuradoRequest;
use App\Http\Requests\PersonaRequest;
use App\Models\Candidato;
use App\Models\Persona;
use App\Http\Controllers\Controller;
use App\Http\Requests\VotanteRequest;
use App\Models\Votante;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $funcionarios = DB::select("SELECT A.*,
                                        CASE
                                            WHEN (SELECT COUNT(*) FROM votantes WHERE persona_id = A.id) = 1 THEN 'Votante'
                                            WHEN (SELECT COUNT(*) FROM candidatos WHERE persona_id = A.id) = 1 THEN 'Candidato'
                                            WHEN (SELECT COUNT(*) FROM jurados WHERE persona_id = A.id) = 1 THEN 'Jurado'
                                            ELSE 'Representante Legal'
                                        END AS tipo_funcionario
                                        FROM personas AS A");

        return response()->json($funcionarios, Response::HTTP_OK);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function find(PersonaRequest  $request)
    {
        $find =$request->numero_documento;
        $existencia = DB::table('personas')
        ->select('numero_documento')
        ->where('numero_documento', '=', $find)
        ->get();
        if(count($existencia) >= 1) {
            return response()->json(["message"=>"Ya Existen Personas Registradas"]);

        }
    }
    public function store(PersonaRequest  $request)
    {
        $find =$request->numero_documento;
        $existencia = DB::table('personas')
        ->select('numero_documento')
        ->where('numero_documento', '=', $find)
        ->get();
        if(count($existencia) >= 1) {
            return response()->json(["message"=>"Ya Existen Personas Registradas"]);

        }else{

        //CREAR PERSONA
        $new_persona= new Persona();
        $new_persona->tipo_documento = $request->tipo_documento;
        $new_persona->numero_documento = $request->numero_documento;
        $new_persona->nombre = $request->nombre;
        $new_persona->apellido = $request->apellido;
        $new_persona->lugar_nacimiento = $request->lugar_nacimiento;
        $new_persona->fecha_nacimiento = $request->fecha_nacimiento;
        $new_persona->save();

        $tipo = $request->input('tipo_funcionario');
        $mesa_id = $request->input('mesa_id');
        $persona_id = $new_persona->id;
        $tipo_id=$request->input('tipo_candidato_id');
        $partido_id=$request->input('partido_id');
        $punto_votacion_id=$request->input('punto_votacion_id');
        /*
            1. Votante
            2. Jurado
            3. Candidato
        */

        //Validar tipo de funcionario
        switch ($tipo) {
            case 1:
                $votanteRequest = new VotanteRequest();
                $votanteRequest->huella = "";
                $votanteRequest->persona_id = $persona_id;
                $votanteRequest->mesa_id = $mesa_id;
                VotanteController::store($votanteRequest);
                # code...
                break;


            case 2:
                $juradoRequest  = new JuradoRequest();
                $juradoRequest->persona_id = $persona_id;
                $juradoRequest->mesa_id = $mesa_id;
                JuradoController::store($juradoRequest);
                break;

            case 3:
                $candidatoRequest  = new CandidatoRequest();
                $candidatoRequest->persona_id = $persona_id;
                $candidatoRequest->numero_inscripcion = random_int(1000,9999);
                $candidatoRequest->partido_id = $partido_id;
                $candidatoRequest->tipo_id = $tipo_id;
                CandidatoController::store($candidatoRequest);
                break;

            default:
                # code...
                break;
        }

        return response()->json(['ok'=>true],Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $persona = DB::select("SELECT A.*,
                            CASE
                                WHEN (SELECT COUNT(*) FROM votantes WHERE persona_id = A.id) = 1 THEN 'Votante'
                                WHEN (SELECT COUNT(*) FROM candidatos WHERE persona_id = A.id) = 1 THEN 'Candidato'
                                WHEN (SELECT COUNT(*) FROM jurados WHERE persona_id = A.id) = 1 THEN 'Jurado'
                                ELSE 'Representante Legal'
                            END AS tipo_funcionario,
                            CASE
                                WHEN (SELECT COUNT(*) FROM votantes WHERE persona_id = A.id) = 1 THEN 1
                                WHEN (SELECT COUNT(*) FROM jurados WHERE persona_id = A.id) = 1 THEN 2
                                WHEN (SELECT COUNT(*) FROM candidatos WHERE persona_id = A.id) = 1 THEN 3
                                ELSE 4
                            END AS tipo_funcionario_id,
                            A.tipo_documento
                            FROM personas AS A
                            WHERE A.id = $id"); 
        
        return $persona;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function update($id,PersonaRequest  $request)
    {
        $persona= Persona::findOrFail($id);
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->numero_documento = $request->get('numero_documento');
        $persona->nombre = $request->get('nombre');
        $persona->apellido = $request->get('apellido');
        $persona->lugar_nacimiento = $request->get('lugar_nacimiento');
        $persona->fecha_nacimiento = $request->get('fecha_nacimiento');


        $persona->save();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $persona=Persona::findOrFail($id);
        $persona->delete();
        return response()->json(['ok'=>true],Response::HTTP_OK);
    }
}
