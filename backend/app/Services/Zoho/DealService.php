<?php

namespace App\Services\Zoho;

use Illuminate\Support\Facades\Http;

final class DealService
{
    public function findDealByNameAndAccountId(string $accessToken, string $dealName, string $accountId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
        ])->get(config('services.zoho.api_url') . '/crm/v7/Deals/search', [
            'criteria' => '(Deal_Name:equals:' . urlencode($dealName) . ') and (Account_Name.id:equals:' . $accountId . ')',
        ]);

        return $response->successful() ? $response->json()['data'][0] ?? null : null;
    }

    public function createDeal(string $accessToken, array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post(config('services.zoho.api_url') . '/crm/v7/Deals', [
            'data' => [$data],
            'trigger' => ['workflow'],
        ]);

        return $response->successful() ? $response->json()['data'][0]['details'] ?? null : null;
    }
}
