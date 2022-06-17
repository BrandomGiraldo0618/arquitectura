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
        $persona = Persona::where('numero_documento', $cedula)->first();
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

        $persona = Persona::where('numero_documento', $documento)->first();

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

        if($partido->listaa_c==true){

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


    public function infoVotosCamara()
    {
        $totalVotosCamaraPartido = DB::Select(" SELECT
                                                    p.nombre AS nombrePartido, 
                                                    p.listaa_c AS tipoLista,
                                                    COUNT(v.id) AS totalVotosPartido
                                                FROM votos v
                                                INNER JOIN partidos p
                                                    ON p.id = v.partido_id
                                                WHERE v.tipo_id = 1
                                                GROUP BY p.nombre;
                                            ");

        $totalVotosCamara = DB::Select("SELECT 
                                            COUNT(v.id) AS totalVotosCamara
                                        FROM votos v
                                        WHERE v.tipo_id = 1;
                                        ");    
                                        
        $totalPartidosCamara = DB::Select("SELECT 
                                            COUNT(1) AS totalPartidosCamara
                                        FROM partidos
                                        WHERE tipo_id = 1;
                                        ");
    
        $candidatosCamara = DB::Select("SELECT 
                                            p.nombre AS nombrePartido,
                                            pr.nombre AS nombreCandidato,
                                            COUNT(v.id)	AS cantidadVotos
                                        FROM candidatos c
                                        INNER JOIN personas pr
                                            ON pr.id = c.persona_id
                                        INNER JOIN partidos p
                                            ON p.id = c.partido_id
                                        INNER JOIN votos v
                                            ON v.partido_id = p.id
                                        WHERE c.tipo_id = 1
                                        GROUP BY p.nombre,
                                                pr.nombre,
                                                c.id
                                        ORDER BY 
                                                COUNT(v.id),
                                                c.id;
                                    ");

        $umbralCamara = $totalVotosCamara / $totalPartidosCamara;

        //Hallar los partidos que superaron el umbral para la camara 
        foreach($totalVotosCamaraPartido as $totalVotoCamaraPartido){

            if($totalVotoCamaraPartido["totalVotosPartido"] > $umbralCamara){

                $totalVotoCamaraPartido["pasoUmbral"] = true;
            }
            else{

                $totalVotoCamaraPartido["pasoUmbral"] = false;
            }
        }


        //Hallar la cantidad de candidatos por partido camara
        foreach($totalVotosCamaraPartido as $totalVotoCamaraPartido){

            if($totalVotoCamaraPartido["pasoUmbral"] = true){

                $totalVotoCamaraPartido["candidadCandidatos"] = floor($totalVotoCamaraPartido["totalVotosPartido"] / 108);
            }
            else{

                $totalVotoCamaraPartido["candidadCandidatos"] = 0;
            }
        }   

        //Hallar los candidatos que pasaron por cada partido de la camara 
        $candidatos = [];
        $candidatosFinal = [];

        foreach($totalVotosCamaraPartido as $totalVotoCamaraPartido){

            if($totalVotoCamaraPartido["pasoUmbral"] = true && $totalVotoCamaraPartido["candidadCandidatos"] > 0){

                foreach($candidatosCamara as $candidatoCamara){

                    if($candidatoCamara["nombrePartido"] = $totalVotoCamaraPartido["nombrePartido"]){

                        $dataCandidato = ["nombreCandidato" => $candidatoCamara["nombreCandidato"],
                                            "cantidadVotos" => $candidatoCamara["cantidadVotos"]
                                        ];
                        array_push($candidatos, $dataCandidato);
                    }
                }
             
                for($i = 0; $i < $totalVotoCamaraPartido["candidadCandidatos"]; $i++ ){

                    array_push($candidatosFinal, $candidatos[i]);

                }

                $totalVotoCamaraPartido["candidatosGanaron"] = $candidatosFinal;

                 // Vaciar listas candidatos y candidatosFinal           
                               
            }else{

                $totalVotoCamaraPartido["candidatosGanaron"] = $candidatos;

            }
        }


        return response()->json($totalVotosCamaraPartido,Response::HTTP_CREATED);
    }

    public function infoVotosSenado()
    {
        

        $totalVotosSenadoPartido = DB::Select("SELECT
                                                p.nombre nombrePartido, 
                                                p.listaa_c tipoLista,
                                                COUNT(v.id) AS totalVotosPartido
                                            FROM votos v
                                            INNER JOIN partidos p
                                                ON p.id = v.partido_id
                                            WHERE v.tipo_id = 2
                                            GROUP BY p.nombre;
                                            ");

    

        $totalVotosSenado = DB::Select("SELECT 
                                            COUNT(v.id) AS totalVotosSenado
                                        FROM votos v
                                        WHERE v.tipo_id = 2;
                                        ");
        


        $totalPartidosSenado = DB::Select("SELECT 
                                                COUNT(1) AS totalPartidosSenado
                                            FROM partidos
                                            WHERE tipo_id = 2;
                                        ");




        $candidatosSenado = DB::Select("SELECT 
                                            p.nombre AS nombrePartido,
                                            pr.nombre AS nombreCandidato,
                                            COUNT(v.id)	AS cantidadVotos
                                        FROM candidatos c
                                        INNER JOIN personas pr
                                            ON pr.id = c.persona_id
                                        INNER JOIN partidos p
                                            ON p.id = c.partido_id
                                        INNER JOIN votos v
                                            ON v.partido_id = p.id
                                        WHERE c.tipo_id = 2
                                        GROUP BY p.nombre,
                                            pr.nombre,
                                            c.id
                                        ORDER BY 
                                            COUNT(v.id),
                                            c.id;
                                        ");                       

       
        $umbralSenado =  $totalVotosSenado /  $totalPartidosSenado;


     
        //Hallar los partidos que superaron el umbral para el senado
        foreach($totalVotosSenadoPartido as $totalVotoSenadoPartido){

            if($totalVotoSenadoPartido["totalVotosPartido"] > $umbralSenado){

                $totalVotoSenadoPartido["pasoUmbral"] = true;
            }
            else{

                $totalVotoSenadoPartido["pasoUmbral"] = false;
            }
        }



        //Hallar la cantidad de candidatos por partido senado
        foreach($totalVotosSenadoPartido as $totalVotoSenadoPartido){

            if($totalVotoSenadoPartido["pasoUmbral"] = true){

                $totalVotoSenadoPartido["candidadCandidatos"] = floor($totalVotoSenadoPartido["totalVotosPartido"] / 108);
            }
            else{

                $totalVotoSenadoPartido["candidadCandidatos"] = 0;
            }
        }

    }

    public function totalVotos()
    {
        $totalVotos = DB::select("SELECT 
                                    COUNT(v.id) AS total_votos_senado
                                        FROM votos v");

        $totalVotosCamara = DB::select("SELECT 
                                COUNT(v.id) AS total_votos
                                    FROM votos v
                                WHERE v.tipo_id = 1");

        $totalVotosSenado = DB::select("SELECT 
                                COUNT(v.id) AS total_votos
                                    FROM votos v
                                WHERE v.tipo_id = 2");

        $totalHabilitados = DB::select("SELECT COUNT(*) AS total
                                            FROM personas AS A
                                                INNER JOIN votantes AS B ON A.id = B.persona_id");
        
        return response()->json(
            [
                "total_votos" => $totalVotos[0]->total_votos_senado,
                "total_votos_camara" => $totalVotosCamara[0]->total_votos,
                "total_votos_senado" => $totalVotosSenado[0]->total_votos,
                "total_habilitados" => $totalHabilitados[0]->total
            ],Response::HTTP_OK);
    }
}
