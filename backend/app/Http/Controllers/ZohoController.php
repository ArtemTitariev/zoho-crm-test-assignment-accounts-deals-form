<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDealAndAccountRequest;
use App\Services\Zoho\DealAccountService;
use \Illuminate\Http\JsonResponse;

class ZohoController extends Controller
{
    public function __construct(
        private DealAccountService $service,
    ) {
        // 
    }

    public function createDealAndAccount(CreateDealAndAccountRequest $request): JsonResponse
    {
        return $this->service->createDealAndAccount($request->validated());
    }
}
