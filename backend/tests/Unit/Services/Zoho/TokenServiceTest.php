<?php

namespace Tests\Unit\Services\Zoho;

use App\Services\Zoho\TokenService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class TokenServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_get_access_token_when_cached()
    {
        $cachedAccessToken = 'cached-access-token';

        Cache::shouldReceive('has')
            ->once()
            ->with('zoho_access_token')
            ->andReturn(true);
        Cache::shouldReceive('get')
            ->once()
            ->with('zoho_access_token')
            ->andReturn($cachedAccessToken);

        $tokenService = new TokenService();

        $accessToken = $tokenService->getAccessToken();

        $this->assertEquals($cachedAccessToken, $accessToken);
    }

    public function test_get_access_token_when_not_cached()
    {
        $newAccessToken = 'new-access-token';

        Http::fake([
            config('services.zoho.accounts_url') . '/oauth/v2/token' => Http::response([
                'access_token' => $newAccessToken,
            ], 200),
        ]);

        Cache::shouldReceive('has')
            ->once()
            ->with('zoho_access_token')
            ->andReturn(false);

        Cache::shouldReceive('put')
            ->once()
            ->with(
                'zoho_access_token',
                'new-access-token',
                Mockery::on(function ($carbon) {
                    return $carbon instanceof \Illuminate\Support\Carbon;
                })
            );

        $tokenService = new TokenService();

        $accessToken = $tokenService->getAccessToken();

        $this->assertEquals($newAccessToken, $accessToken);
    }

    public function test_get_access_token_when_api_fails()
    {
        Http::fake([
            config('services.zoho.accounts_url') . '/oauth/v2/token' => Http::response([], 400),
        ]);

        Cache::shouldReceive('has')
            ->once()
            ->with('zoho_access_token')
            ->andReturn(false);

        $tokenService = new TokenService();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(__('Zoho CRM token failed to update.'));

        $tokenService->getAccessToken();
    }
}
