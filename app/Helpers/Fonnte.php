<?php

namespace App\Helpers;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class Fonnte
{
    public static function kirim($data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => config('whatsapp.curlopt_url'),
            CURLOPT_RETURNTRANSFER  => config('whatsapp.curlopt_returntransfer'),
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => config('whatsapp.curlopt_maxredirs'),
            CURLOPT_TIMEOUT         => config('whatsapp.curlopt_timeout'),
            CURLOPT_FOLLOWLOCATION  => config('whatsapp.curlopt_followlocation'),
            CURLOPT_HTTP_VERSION    => config('whatsapp.curlopt_http_version'),
            CURLOPT_CUSTOMREQUEST   => config('whatsapp.curlopt_customrequest'),
            CURLOPT_POSTFIELDS      => array(
                'phone' => $data['phone'],
                'type' => 'text',
                'text' => $data['text'],
                'delay' => '1',
                'schedule' => '0'
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization:" . config('whatsapp.token')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
