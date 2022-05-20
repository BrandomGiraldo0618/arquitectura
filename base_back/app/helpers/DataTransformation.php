<?php

function getActive($active)
{
    $status = false;

    if ($active == 1) {
        $status = true;
    }

    return $status;
}

/** Obtener mesajes de registro */
function getLogMessages($id)
{
    $message = '';

    if (0 == $id) {
        $message = trans('messages.created');
    } else {
        $message = trans('messages.updated');
    }

    return $message;
}

/**
 * Permite obtener el indice de calidad del aire (ICA) para el contaminante PM2.5
 * @param float $cp = Concentración medida para el contaminante PM2.5
 * @var int $ica['pc_high'] = Punto de corte mayor o igual a $cp
 * @var int $ica['pc_low'] = Punto de corte menor o igual a $cp
 * @var int $ica['i_high'] = Valor del ICA correspondiente a $ica['pc_high']
 * @var int $ica['i_low'] = Valor del ICA correspondiente a $ica['pc_low']
 * @return float $icap = Indice de calidad del aire
 */
function getValueFromIca($cp)
{
    $icas = config('device_config.ica_breakpoints'); // Se obtienen los puntos de corte del ICA

    foreach($icas as $ica) {
        if($cp >= $ica['pc_low'] && $cp <= $ica['pc_high']) {
            $subtraction_i = $ica['i_high'] - $ica['i_low'];
            $subtraction_pc = $ica['pc_high'] - $ica['pc_low'];

            $division_i_pc = $subtraction_i / $subtraction_pc;

            $subtraction_pc_pc_high = $cp - $ica['pc_low'];

            $icap = $division_i_pc * $subtraction_pc_pc_high + $ica['i_low'];
            
            return round($icap, 3);
        }
    }
}
