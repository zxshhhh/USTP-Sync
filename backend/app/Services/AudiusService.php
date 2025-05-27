<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AudiusService
{
    protected string $baseUrl;
    protected string $appName;

    public function __construct()
    {
        $this->baseUrl = config('services.audius.base_url');
        $this->appName = config('services.audius.app_name');
    }

    public function trending()
    {
        return Http::get("{$this->baseUrl}/tracks/trending", [
            'app_name' => $this->appName
        ])->json()['data'];
    }

    public function search(string $query)
    {
        return Http::get("{$this->baseUrl}/tracks/search", [
            'query' => $query,
            'app_name' => $this->appName
        ])->json()['data'];
    }
}