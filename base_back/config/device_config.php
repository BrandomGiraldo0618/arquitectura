<?php
return [
    "ica_breakpoints" => json_decode(file_get_contents(__DIR__.'/device_configs/ica_breakpoints.json'), true)
];