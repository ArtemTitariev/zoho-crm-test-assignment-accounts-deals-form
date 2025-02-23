<?php

namespace Tests\Feature;

use App\Services\Zoho\AccountService;
use App\Services\Zoho\DealService;
use App\Services\Zoho\TokenService;
use Mockery;
use Tests\TestCase;

class ZohoControllerTest extends TestCase
{
    private string $accessToken;
    private string $accountName;
    private string $website;
    private string $phone;
    private string $dealName;
    private string $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessToken = 'access-token';
        $this->accountName = 'Test Account';
        $this->website = 'https://example.com';
        $this->phone = '1234567890';
        $this->dealName = 'Test Deal';
        $this->stage = 'Test stage';
    }

    private function mockTokenService()
    {
        $mockTokenService = Mockery::mock(TokenService::class);
        $mockTokenService->shouldReceive('getAccessToken')
            ->once()
            ->andReturn($this->accessToken);

        $this->app->instance(TokenService::class, $mockTokenService);
    }

    private function makeRequest()
    {
        return $this->postJson('/api/zoho/create-deal-and-account', [
            'account_name' => $this->accountName,
            'account_website' => $this->website,
            'account_phone' => $this->phone,
            'deal_name' => $this->dealName,
            'deal_stage' => $this->stage,
        ]);
    }

    public function test_create_deal_and_account_success()
    {
        $this->mockTokenService();

        $mockAccountService = Mockery::mock(AccountService::class);
        $mockAccountService->shouldReceive('findAccountByName')
            ->once()
            ->with($this->accessToken, $this->accountName)
            ->andReturn(null);

        $mockAccountService->shouldReceive('createAccount')
            ->once()
            ->with($this->accessToken, [
                'Account_Name' => $this->accountName,
                'Website' => $this->website,
                'Phone' => $this->phone,
            ])
            ->andReturn(['id' => 123]);

        $mockDealService = Mockery::mock(DealService::class);
        $mockDealService->shouldReceive('findDealByNameAndAccountId')
            ->once()
            ->with($this->accessToken, $this->dealName, 123)
            ->andReturn(null);

        $mockDealService->shouldReceive('createDeal')
            ->once()
            ->with($this->accessToken, [
                'Deal_Name' => $this->dealName,
                'Stage' => $this->stage,
                'Account_Name' => ['id' => 123],
            ])
            ->andReturn(['id' => 321]);

        $this->app->instance(AccountService::class, $mockAccountService);
        $this->app->instance(DealService::class, $mockDealService);

        $response = $this->makeRequest();

        $response->assertStatus(200)
            ->assertJson([
                'message' => __('Deal and account successfully created!'),
                'account_id' => 123,
                'deal_id' => 321,
            ]);
    }

    public function test_create_deal_and_account_existing_account()
    {
        $this->mockTokenService();

        $mockAccountService = Mockery::mock(AccountService::class);
        $mockAccountService->shouldReceive('findAccountByName')
            ->once()
            ->with($this->accessToken, $this->accountName)
            ->andReturn([
                'id' => 123,
                'Account_Name' => $this->accountName,
                'Website' => $this->website,
                'Phone' => $this->phone,
            ]);

        $mockDealService = Mockery::mock(DealService::class);
        $mockDealService->shouldReceive('findDealByNameAndAccountId')
            ->once()
            ->with($this->accessToken, $this->dealName, 123)
            ->andReturn(null);

        $mockDealService->shouldReceive('createDeal')
            ->once()
            ->with($this->accessToken, [
                'Deal_Name' => $this->dealName,
                'Stage' => $this->stage,
                'Account_Name' => ['id' => 123],
            ])
            ->andReturn(['id' => 321]);

        $this->app->instance(AccountService::class, $mockAccountService);
        $this->app->instance(DealService::class, $mockDealService);

        $response = $this->makeRequest();

        $response->assertStatus(200)
            ->assertJson([
                'message' => __('Deal and account successfully created!'),
                'account_id' => 123,
                'deal_id' => 321,
            ]);
    }

    public function test_create_deal_and_account_existing_account_existing_deal()
    {
        $this->mockTokenService();

        $mockAccountService = Mockery::mock(AccountService::class);
        $mockAccountService->shouldReceive('findAccountByName')
            ->once()
            ->with($this->accessToken, $this->accountName)
            ->andReturn([
                'id' => 123,
                'Account_Name' => $this->accountName,
                'Website' => $this->website,
                'Phone' => $this->phone,
            ]);

        $mockDealService = Mockery::mock(DealService::class);
        $mockDealService->shouldReceive('findDealByNameAndAccountId')
            ->once()
            ->with($this->accessToken, $this->dealName, 123)
            ->andReturn(['id' => 321]);

        $this->app->instance(AccountService::class, $mockAccountService);
        $this->app->instance(DealService::class, $mockDealService);

        $response = $this->makeRequest();

        $response->assertStatus(400)
            ->assertJson([
                'message' => __('Such a deal already exists for this account'),
                'errors' => [
                    'deal_name' => [
                        __('Such a deal already exists for this account'),
                    ],
                ],
                'deal_id' => 321,
            ]);
    }

    public function test_create_deal_and_account_error_creating_account()
    {
        $this->mockTokenService();

        $mockAccountService = Mockery::mock(AccountService::class);
        $mockAccountService->shouldReceive('findAccountByName')
            ->once()
            ->with($this->accessToken, $this->accountName)
            ->andReturn(null);

        $mockAccountService->shouldReceive('createAccount')
            ->once()
            ->with($this->accessToken, [
                'Account_Name' => $this->accountName,
                'Website' => $this->website,
                'Phone' => $this->phone,
            ])
            ->andReturn(null);

        $mockDealService = Mockery::mock(DealService::class);

        $this->app->instance(AccountService::class, $mockAccountService);
        $this->app->instance(DealService::class, $mockDealService);

        $response = $this->makeRequest();

        $response->assertStatus(500)
            ->assertJson([
                'message' => __('Error while creating an account'),
            ]);
    }

    public function test_create_deal_and_account_error_creating_deal()
    {
        $this->mockTokenService();

        $mockAccountService = Mockery::mock(AccountService::class);
        $mockAccountService->shouldReceive('findAccountByName')
            ->once()
            ->with($this->accessToken, $this->accountName)
            ->andReturn([
                'id' => 123,
                'Account_Name' => $this->accountName,
                'Website' => $this->website,
                'Phone' => $this->phone,
            ]);

        $mockDealService = Mockery::mock(DealService::class);
        $mockDealService->shouldReceive('findDealByNameAndAccountId')
            ->once()
            ->with($this->accessToken, $this->dealName, 123)
            ->andReturn(null);

        $mockDealService->shouldReceive('createDeal')
            ->once()
            ->with($this->accessToken, [
                'Deal_Name' => $this->dealName,
                'Stage' => $this->stage,
                'Account_Name' => ['id' => 123],
            ])
            ->andReturn(null);

        $this->app->instance(AccountService::class, $mockAccountService);
        $this->app->instance(DealService::class, $mockDealService);

        $response = $this->makeRequest();

        $response->assertStatus(500)
            ->assertJson([
                'message' => __('Error while creating a deal'),
            ]);
    }
}
