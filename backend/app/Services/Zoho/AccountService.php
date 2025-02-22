<?php

namespace App\Services\Zoho;

use Illuminate\Support\Facades\Http;

class AccountService
{
    public function findAccountByName(string $accessToken, string $accountName)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
        ])->get(config('services.zoho.api_url') . '/crm/v7/Accounts/search', [
            'criteria' => '(Account_Name:equals:' . urlencode($accountName) . ')',
        ]);

        return $response->successful() ? $response->json()['data'][0] ?? null : null;
    }

    public function createAccount(string $accessToken, array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post(config('services.zoho.api_url') . '/crm/v7/Accounts', [
            'data' => [$data],
            'trigger' => ['workflow'],
        ]);

        return $response->successful() ? $response->json()['data'][0]['details'] ?? null : null;
    }
}
