<?php

namespace Tests\Unit\Services\Zoho;

use App\Services\Zoho\AccountService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    public function test_find_account_by_name_success()
    {
        $accessToken = 'access-token';
        $accountId = '123';
        $accountName = 'Test account';

        Http::fake([
            config('services.zoho.api_url') . '/crm/v7/Accounts/search*' => Http::response([
                'data' => [
                    [
                        'id' => $accountId,
                        'Account_Name' => $accountName,
                    ],
                ],
            ], 200),
        ]);


        $service = new AccountService();
        $account = $service->findAccountByName($accessToken, $accountName);

        $this->assertNotNull($account);
        $this->assertEquals($accountId, $account['id']);
        $this->assertEquals($accountName, $account['Account_Name']);
    }

    public function test_find_account_by_name_not_found()
    {
        $accessToken = 'access-token';
        $accountName = 'Test account';

        Http::fake([
            config('services.zoho.api_url') . '/crm/v7/Accounts/search*' => Http::response(null, 204),
        ]);

        $service = new AccountService();
        $account = $service->findAccountByName($accessToken, $accountName);

        $this->assertNull($account);
    }

    public function test_create_account_success()
    {
        $accountId = '123';

        Http::fake([
            config('services.zoho.api_url') . '/crm/v7/Accounts' => Http::response([
                'data' => [
                    [
                        'details' => [
                            'id' => $accountId,
                        ],
                    ]
                ]
            ], 201)
        ]);

        $service = new AccountService();
        $accessToken = 'access-token';
        $data = ['Account_Name' => 'New Account'];

        $accountDetails = $service->createAccount($accessToken, $data);

        $this->assertNotNull($accountDetails);
        $this->assertEquals($accountId, $accountDetails['id']);
    }

    public function test_create_account_fails()
    {
        Http::fake([
            config('services.zoho.api_url') . '/crm/v7/Accounts' => Http::response([], 400)
        ]);

        $service = new AccountService();
        $accessToken = 'access-token';
        $data = [];

        $accountDetails = $service->createAccount($accessToken, $data);

        $this->assertNull($accountDetails);
    }
}
