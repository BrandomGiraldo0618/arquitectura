<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Library\General;
use App\Models\Device;
use App\Models\DeviceCaptureLog;
use App\Models\DeviceCaptureLog1M;
use App\Models\DeviceCaptureLog5M;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    private const MMHG = 0.750064;

    public function step1()
    {
        $devices = Device::where('active' , true)->get();

        if(!empty($devices))
        {
            foreach($devices as $device)
            {
                $url = config('app.purpleair_api').'?show='.$device->device_id.'&key='.$device->key;

                $result = General::curl($url,'GET');

                DB::beginTransaction();

                $deviceCapture = new DeviceCaptureLog1M;
                $deviceCapture->device_id = $device->id;
                $deviceCapture->capture =  $result;

                if(!$deviceCapture->save())
                {
                    DB::rollback();
                }

                DB::commit();
            }
        }
    }

    public function step2()
    {
        $devices = Device::where('active', true)->get();

        $this->iterateStructure($devices, $this->getStructure(), 0, 0);
    }

    /**
     * Funcion recursiva que recorre todos los dispositivos activos y saca el promedio de
     * los ultimos 5 minutos de las variables: material particulado, humedad, temperatura,
     * presion atmosférica y elevación
     * @param Device $devices
     */
    private function iterateStructure($devices, array $structure, int $i, int $j)
    {
        if($i < $devices->count('id')) {
            if($devices[$i]->deviceslogs1m != '[]') {
                if($j <= 4) {
                    $deviceCaptureLog = json_decode($devices[$i]->deviceslogs1m[$j], true);
                    $capture = json_decode($deviceCaptureLog['capture'], true);

                    $structure = $this->assignValuesToTheStructure($structure, $capture, $deviceCaptureLog['created_at']);

                    $j++;

                    $this->iterateStructure($devices, $structure, $i, $j);
                } else {
                    $this->saveStructure($structure, $devices[$i]->id, $j);

                    $i++;
                    $j = 0;

                    $this->iterateStructure($devices, $this->getStructure(), $i, $j);
                }
            }
        }
    }

    /** Estructura que contendra las variables promediadas */
    private function getStructure()
    {
        return [
            'date' => '',
            'data_average' => [
                'pm_cf_1' => 0, // Promedio material particulado
                'humidity' => 0, // Promedio humedad
                'temperature' => 0, // Promedio temperatura
                'pressure' => 0, // Promedio presion
                'elevation' => 0 // Promedio elevacion
            ],
            'data_json' => []
        ];
    }

    /** Asigna los valores haciendo una sumatoria de los ultimos cinco minutos*/
    private function assignValuesToTheStructure($structure, $capture, $date_created_at)
    {
        // Sumatoria de variables
        $structure['data_average']['pm_cf_1'] += $capture['data'][0][2];
        $structure['data_average']['humidity'] += $capture['data'][0][21];
        $structure['data_average']['temperature'] += $capture['data'][0][22]; // ¿Conversión de temperatura?
        $structure['data_average']['pressure'] += $capture['data'][0][23];
        $structure['data_average']['elevation'] += $capture['data'][0][24];

        $structure['data_json'][] = [ // Datos de todas las variables
            'date_created_at' => $date_created_at,
            'data' => $capture['data'][0]
        ];

        return $structure;
    }

    /** Guarda los promedios */
    private function saveStructure($structure, $device_id, $j)
    {
        $structure['date'] = date('Y-m-d H:i:s');

        // Promedio de variables
        $structure['data_average']['pm_cf_1'] = getValueFromIca($structure['data_average']['pm_cf_1'] / $j); // Se obtiene el indice de calidad del aire
        $structure['data_average']['humidity'] = $structure['data_average']['humidity'] / $j; // Este dato es un porcentaje
        $structure['data_average']['temperature'] = (($structure['data_average']['temperature'] / $j) - 32) / 1.8; // Se pasa de grados fahrenheit a grados centigrados
        $structure['data_average']['pressure'] = ($structure['data_average']['pressure'] / $j) * self::MMHG; // Se convierte presion en Hectopascales a milimetros de mercurio
        $structure['data_average']['elevation'] = $structure['data_average']['elevation'] / $j;

        DB::beginTransaction();
        $deviceCaptureLog = new DeviceCaptureLog5M();
        $deviceCaptureLog->device_id = $device_id;
        $deviceCaptureLog->capture = json_encode($structure);
        $deviceCaptureLog->save() ?: DB::rollback();
        DB::commit();
    }
}
