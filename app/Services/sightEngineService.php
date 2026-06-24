<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SightengineService
{
    protected $apiUser;
    protected $apiSecret;

    public function __construct()
    {
        $this->apiUser = env('SIGHTENGINE_API_USER');
        $this->apiSecret = env('SIGHTENGINE_API_SECRET');
    }

    public function checkImage($imagePath)
    {
        $response = Http::attach(
            'media',
            file_get_contents($imagePath),
            basename($imagePath)
        )->post('https://api.sightengine.com/1.0/check.json', [
            'models' => 'nudity-2.1,weapon,gore,genai',
            'api_user' => $this->apiUser,
            'api_secret' => $this->apiSecret,
        ]);

        return $response->json();
    }
}