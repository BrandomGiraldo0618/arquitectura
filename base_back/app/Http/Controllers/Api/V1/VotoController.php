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
                                                    p.nombre AS nombre_partido, 
                                                    p.listaa_c AS tipo_lista,
                                                    COUNT(v.id) AS total_votos_partido
                                                FROM votos v
                                                INNER JOIN partidos p
                                                    ON p.id = v.partido_id
                                                WHERE v.tipo_id = 1
                                                GROUP BY p.nombre,
                                                p.listaa_c
                                                ORDER BY COUNT(v.id) DESC;");

        $totalVotosCamara = DB::Select("SELECT 
                                            COUNT(v.id) AS total_votos_camara
                                        FROM votos v
                                        WHERE v.tipo_id = 1;
                                        ");    
                                        
        $totalPartidosCamara = DB::Select("SELECT 
                                            COUNT(1) AS total_partidos_camara
                                        FROM partidos
                                        WHERE tipo_id = 1;
                                        ");
    
        $candidatosCamara = DB::Select("SELECT 
                                            p.nombre AS nombre_partido,
                                            pr.nombre AS nombre_candidato,
                                            COUNT(*) AS cantidad_votos
                                        FROM votos v
                                            INNER JOIN partidos p
                                                ON p.id = v.partido_id
                                            INNER JOIN candidatos c
                                                ON c.id = v.candidato_id
                                            INNER JOIN personas pr
                                                ON pr.id = c.persona_id
                                        WHERE v.tipo_id = 1
                                        GROUP BY p.nombre, pr.nombre
                                        ORDER BY 3 DESC;");

        $totalVotosCamara = $totalVotosCamara[0]->total_votos_camara;
        $totalPartidosCamara = $totalPartidosCamara[0]->total_partidos_camara;
        $umbralCamara = $totalVotosCamara / $totalPartidosCamara;
   
        //Hallar los partidos que superaron el umbral para la camara 
        for ($i=0; $i < sizeof($totalVotosCamaraPartido); $i++) { 
            if($totalVotosCamaraPartido[$i]->total_votos_partido > $umbralCamara){
                $totalVotosCamaraPartido[$i]->paso_umbral =  true;     
            }
            else{
                $totalVotosCamaraPartido[$i]->paso_umbral = false;                
            }     
        }

        //Hallar la cantidad de candidatos por partido camara

        for ($i=0; $i < sizeof($totalVotosCamaraPartido); $i++) { 
            if($totalVotosCamaraPartido[$i]->paso_umbral == true){

                $totalVotosCamaraPartido[$i]->candidad_candidatos = floor($totalVotosCamaraPartido[$i]->total_votos_partido / 108);
            }
            else{
                $totalVotosCamaraPartido[$i]->candidad_candidatos = 0;
            }
        }


        //Hallar los candidatos que pasaron por cada partido de la camara         
        for ($i=0; $i < sizeof($totalVotosCamaraPartido); $i++) { 
            $candidatos = [];
            $candidatos_final = [];

            if($totalVotosCamaraPartido[$i]->paso_umbral == true && $totalVotosCamaraPartido[$i]->candidad_candidatos > 0){

                for ($j=0; $j < sizeof($candidatosCamara); $j++) { 
                  
                    if($candidatosCamara[$j]->nombre_partido == $totalVotosCamaraPartido[$i]->nombre_partido){

                        $data_candidato = ["nombre_candidato" => $candidatosCamara[$j]->nombre_candidato,
                                            "cantidad_votos" => $candidatosCamara[$j]->cantidad_votos
                                        ];
                        array_push($candidatos, $data_candidato);
                    }
                }

                if(sizeof($candidatos) < $totalVotosCamaraPartido[$i]->candidad_candidatos){
                    array_push($candidatos_final, $candidatos);
                }else {
                    for($k = 0; $k < $totalVotosCamaraPartido[$i]->candidad_candidatos; $k++ ){                                    
                        array_push($candidatos_final, $candidatos[$k]);    
                    }
                } 

                $totalVotosCamaraPartido[$i]->candidatos_ganaron = $candidatos_final;                             
            }else{
                $totalVotosCamaraPartido[$i]->candidatos_ganaron = $candidatos;
            }
        }

        return response()->json($totalVotosCamaraPartido,Response::HTTP_CREATED);
    }

    public function infoVotosSenado()
    { 
        $totalVotosSenadoPartido = DB::Select("SELECT
                                                p.nombre nombre_partido, 
                                                p.listaa_c tipo_lista,
                                                COUNT(v.id) AS total_votos_partido
                                            FROM votos v
                                            INNER JOIN partidos p
                                                ON p.id = v.partido_id
                                            WHERE v.tipo_id = 2
                                            GROUP BY p.nombre,
                                                    p.listaa_c 
                                            ORDER BY COUNT(v.id) DESC;");

        $totalVotosSenado = DB::Select("SELECT 
                                            COUNT(v.id) AS total_votos_senado
                                        FROM votos v
                                        WHERE v.tipo_id = 2;");    

        $totalPartidosSenado = DB::Select("SELECT 
                                                COUNT(1) AS total_partidos_senado
                                            FROM partidos
                                            WHERE tipo_id = 2;");

        $candidatosSenado = DB::Select("SELECT 
                                            p.nombre AS nombre_partido,
                                            pr.nombre AS nombre_candidato,
                                            COUNT(*) AS cantidad_votos
                                        FROM votos v
                                            INNER JOIN partidos p
                                                ON p.id = v.partido_id
                                            INNER JOIN candidatos c
                                                ON c.id = v.candidato_id
                                            INNER JOIN personas pr
                                                ON pr.id = c.persona_id
                                        WHERE v.tipo_id = 2
                                        GROUP BY p.nombre, pr.nombre
                                        ORDER BY 3 DESC;");                       

        $totalVotosSenado = $totalVotosSenado[0]->total_votos_senado;
        $totalPartidosSenado = $totalPartidosSenado[0]->total_partidos_senado;     
        $umbralSenado =  $totalVotosSenado /  $totalPartidosSenado;

        //Hallar los partidos que superaron el umbral para la camara 
        for ($i=0; $i < sizeof($totalVotosSenadoPartido); $i++) { 
            if($totalVotosSenadoPartido[$i]->total_votos_partido > $umbralSenado){
                $totalVotosSenadoPartido[$i]->paso_umbral =  true;
        
            }
            else{
                $totalVotosSenadoPartido[$i]->paso_umbral =  false;                
            }     
        }        

        //Hallar la cantidad de candidatos por partido camara

        for ($i=0; $i < sizeof($totalVotosSenadoPartido); $i++) { 
            if($totalVotosSenadoPartido[$i]->paso_umbral == true){

                $totalVotosSenadoPartido[$i]->candidad_candidatos = floor($totalVotosSenadoPartido[$i]->total_votos_partido / 108);
            }
            else{

                $totalVotosSenadoPartido[$i]->candidad_candidatos = 0;
            }
        }


        //Hallar los candidatos que pasaron por cada partido de la camara 
 
        
        for ($i=0; $i < sizeof($totalVotosSenadoPartido); $i++) { 
            $candidatos = [];
            $candidatos_final = [];

            if($totalVotosSenadoPartido[$i]->paso_umbral == true && $totalVotosSenadoPartido[$i]->candidad_candidatos > 0){

                for ($j=0; $j < sizeof($candidatosSenado); $j++) { 
                  
                    if($candidatosSenado[$j]->nombre_partido == $totalVotosSenadoPartido[$i]->nombre_partido){

                        $data_candidato = ["nombre_candidato" => $candidatosSenado[$j]->nombre_candidato,
                                            "cantidad_votos" => $candidatosSenado[$j]->cantidad_votos
                                        ];
                        array_push($candidatos, $data_candidato);
                    }
                }

                if(sizeof($candidatos) < $totalVotosSenadoPartido[$i]->candidad_candidatos){
                    array_push($candidatos_final, $candidatos);
                }else{
                    for($k = 0; $k < $totalVotosSenadoPartido[$i]->candidad_candidatos; $k++ ){
                                    
                        array_push($candidatos_final, $candidatos[$k]);    
                    }
                }                

                $totalVotosSenadoPartido[$i]->candidatos_ganaron = $candidatos_final;
                             
            }else{
                $totalVotosSenadoPartido[$i]->candidatos_ganaron = $candidatos;
            }
        }

        return response()->json($totalVotosSenadoPartido,Response::HTTP_CREATED);  

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
