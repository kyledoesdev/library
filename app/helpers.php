<?php

function get_timezone(): string
{
    $ip = 'http://ip-api.com/json/'.request()->ip();

    if (config('app.env') !== 'production') {
        return "America/New_York";
    }

    return json_decode(file_get_contents($ip), true)['timezone'];
}