<?php

namespace App\Services\Zoho;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TokenService
{
    private string $clientId;
    private string $clientSecret;
    private string $refreshToken;

    public function __construct()
    {
        $this->clientId = config('services.zoho.client_id');
        $this->clientSecret = config('services.zoho.client_secret');
        $this->refreshToken = config('services.zoho.refresh_token');
    }

    public function getAccessToken()
    {
        if (Cache::has('zoho_access_token')) {
            return Cache::get('zoho_access_token');
        }

        return $this->refreshAccessToken();
    }

    private function refreshAccessToken()
    {
        $response = Http::asForm()->post(config('services.zoho.accounts_url') . '/oauth/v2/token', [
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $accessToken = $data['access_token'];

            Cache::put('zoho_access_token', $accessToken, now()->addMinutes(55));

            return $accessToken;
        }

        throw new \Exception(__('Zoho CRM token failed to update.'));
    }
}
