<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDealAndAccountRequest;
use App\Services\Zoho\AccountService;
use App\Services\Zoho\DealService;
use App\Services\Zoho\TokenService;
use \Illuminate\Http\JsonResponse;

class ZohoController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private DealService $dealService,
        private TokenService $tokenService,
    ) {
        // 
    }

    public function createDealAndAccount(CreateDealAndAccountRequest $request)
    {
        $validated = $request->validated();
        $accessToken = $this->tokenService->getAccessToken();

        $accountId = $this->getOrCreateAccount($accessToken, $validated);
        if ($accountId instanceof JsonResponse) {
            return $accountId;
        }

        return $this->createDeal($accessToken, $validated, $accountId);
    }

    private function getOrCreateAccount(string $accessToken, array $validated)
    {
        $existingAccount = $this->accountService->findAccountByName($accessToken, $validated['account_name']);

        if ($existingAccount) {
            if ($this->isAccountDataMismatched($existingAccount, $validated)) {
                return response()->json([
                    'message' => __('An account with that name and a different website or phone already exists'),
                    'errors' => [
                        'account_name' => [__('An account with that name already exists')],
                    ],
                ], 400);
            }

            return $existingAccount['id'];
        }

        return $this->createAccount($accessToken, $validated);
    }

    private function isAccountDataMismatched(array $existingAccount, array $validated): bool
    {
        return $existingAccount['Website'] !== $validated['account_website'] ||
            $existingAccount['Phone'] !== $validated['account_phone'];
    }

    private function createAccount(string $accessToken, array $validated)
    {
        $accountData = [
            'Account_Name' => $validated['account_name'],
            'Website' => $validated['account_website'],
            'Phone' => $validated['account_phone'],
        ];

        $account = $this->accountService->createAccount($accessToken, $accountData);

        if (!$account) {
            return response()->json([
                'message' => __('Error while creating an account'),
            ], 500);
        }

        return $account['id'];
    }

    private function createDeal(string $accessToken, array $validated, string $accountId)
    {
        $existingDeal = $this->dealService->findDealByNameAndAccountId($accessToken, $validated['deal_name'], $accountId);

        if ($existingDeal) {
            return response()->json([
                'message' => __('Such a deal already exists for this account'),
                'errors' => [
                    'deal_name' => [
                        __('Such a deal already exists for this account'),
                    ],
                ],
                'deal_id' => $existingDeal['id'],
            ], 400);
        }

        $dealData = [
            'Deal_Name' => $validated['deal_name'],
            'Stage' => $validated['deal_stage'],
            'Account_Name' => ['id' => $accountId],
        ];

        $deal = $this->dealService->createDeal($accessToken, $dealData);

        if (!$deal) {
            return response()->json([
                'message' => __('Error while creating a deal'),
            ], 500);
        }

        return response()->json([
            'message' => __('Deal and account successfully created!'),
            'account_id' => $accountId,
            'deal_id' => $deal['id'],
        ]);
    }
}
