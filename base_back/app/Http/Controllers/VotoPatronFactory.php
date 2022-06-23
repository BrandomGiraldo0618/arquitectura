<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class VotoPatronFactory
{
    public function infoVotosCamaraQuery($consulta)
    {

        if ($consulta == 1) {
            $query = $this->consultarBD("SELECT p.nombre AS nombre_partido,
                                                    p.listaa_c AS tipo_lista,
                                                    COUNT(v.id) AS total_votos_partido
                                                FROM votos v
                                                INNER JOIN partidos p
                                                    ON p.id = v.partido_id
                                                WHERE v.tipo_id = 1
                                                GROUP BY p.nombre,
                                                p.listaa_c
                                                ORDER BY COUNT(v.id) DESC;");
        }
        if ($consulta == 2) {
            $query = $this->consultarBD("SELECT COUNT(v.id) AS total_votos_camara
                                        FROM votos v
                                        WHERE v.tipo_id = 1;
                                        ");
        }
        if ($consulta == 3) {
            $query = $this->consultarBD("SELECT COUNT(1) AS total_partidos_camara
                                        FROM partidos
                                        WHERE tipo_id = 1;
                                        ");
        }
        if ($consulta == 4) {
            $query = $this->consultarBD("SELECT 
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
                                        ORDER BY 3 DESC;
                                    ");
        }
        return $query;
    }

    public function infoVotosSenadoQuery($consulta)
    {
        if ($consulta == 1) {
            $query = $this->consultarBD("SELECT p.nombre nombre_partido,
                                                p.listaa_c tipo_lista,
                                                COUNT(v.id) AS total_votos_partido
                                            FROM votos v
                                            INNER JOIN partidos p
                                                ON p.id = v.partido_id
                                            WHERE v.tipo_id = 2
                                            GROUP BY p.nombre,
                                                    p.listaa_c
                                            ORDER BY COUNT(v.id) DESC;");
        }
        if ($consulta == 2) {
            $query = $this->consultarBD("SELECT COUNT(v.id) AS total_votos_senado
                                        FROM votos v
                                        WHERE v.tipo_id = 2;");
        }
        if ($consulta == 3) {
            $query = $this->consultarBD("SELECT COUNT(1) AS total_partidos_senado
                                            FROM partidos
                                            WHERE tipo_id = 2;");
        }
        if ($consulta == 4) {
            $query = $this->consultarBD("SELECT 
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
        }

        return $query;
    }

    public function totalVotosQuery($consulta)
    {
        if ($consulta == 1) {
            
            $consultaquery="SELECT COUNT(v.id) AS total_votos_senado FROM votos v;";
           
            $query = $this->consultarBD($consultaquery);
        }
        if ($consulta == 2) {
            $query = $this->consultarBD("SELECT COUNT(v.id) AS total_votos
                                    FROM votos v
                                WHERE v.tipo_id = 1");
        }
        if ($consulta == 3) {
            $query = $this->consultarBD("SELECT COUNT(v.id) AS total_votos
                                    FROM votos v
                                WHERE v.tipo_id = 2");
        }
        if ($consulta == 4) {
            $query =  $this->consultarBD("SELECT COUNT(*) AS total
                                            FROM personas AS A
                                                INNER JOIN votantes AS B ON A.id = B.persona_id");
        }

        return $query;
    }
     private function consultarBD($consulta){
       $query= DB::select($consulta);
       
       return $query;

    }
}
