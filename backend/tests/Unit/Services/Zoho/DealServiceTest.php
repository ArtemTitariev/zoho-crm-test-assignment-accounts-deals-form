<?php

namespace Tests\Unit\Services\Zoho;

use App\Services\Zoho\DealService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DealServiceTest extends TestCase
{
    public function test_find_deal_by_name_and_account_id_success()
    {
        $accessToken = 'access-token';
        $accountId = '123';
        $dealName = 'Test deal';

        Http::fake([
            config('services.zoho.api_url') . '/crm/v7/Deals/search*' => Http::response([
                'data' => [
                    [
                        'id' => $accountId,
                        'Deal_Name' => $dealName,
                        'Account_Name' => [
                            'id' => $accountId,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $service = new DealService();
        $deal = $service->findDealByNameAndAccountId($accessToken, $dealName, $accountId);

        $this->assertNotNull($deal);
        $this->assertEquals($dealName, $deal['Deal_Name']);
        $this->assertEquals($accountId, $deal['Account_Name']['id']);
    }

    public function test_find_deal_by_name_and_account_id_not_found()
    {
        $accessToken = 'access-token';
        $accountId = '123';
        $dealName = 'Test deal';

        Http::fake([
            config('services.zoho.api_url') . '/crm/v7/Deals/search*' => Http::response(null, 204),
        ]);

        $service = new DealService();
        $deal = $service->findDealByNameAndAccountId($accessToken, $dealName, $accountId);

        $this->assertNull($deal);
    }

    public function test_create_deal_success()
    {
        $dealId = '321';

        Http::fake([
            config('services.zoho.api_url') . '/crm/v7/Deals' => Http::response([
                'data' => [
                    [
                        'details' => [
                            'id' => $dealId,
                        ],
                    ]
                ]
            ], 201)
        ]);

        $service = new DealService();
        $accessToken = 'access-token';
        $data = [
            'Deal_Name' => 'New Deal',
            "Stage" => "test",
            "Account_Name" => '123',
        ];

        $dealDetails = $service->createDeal($accessToken, $data);

        $this->assertNotNull($dealDetails);
        $this->assertEquals($dealId, $dealDetails['id']);
    }

    public function test_create_deal_fails()
    {
        Http::fake([
            config('services.zoho.api_url') . '/crm/v7/Deals' => Http::response([], 400)
        ]);

        $service = new DealService();
        $accessToken = 'access-token';
        $data = [];

        $dealDetails = $service->createDeal($accessToken, $data);

        $this->assertNull($dealDetails);
    }
}
