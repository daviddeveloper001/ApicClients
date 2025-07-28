<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ApiClient;
use App\Filters\ApiClientFilter;
use App\Services\Api\V1\ApiClientServiceV1;
use App\Http\Controllers\Api\V1\ApiControllerV1;
use App\Http\Resources\Api\V1\ApiClient\ApiClientResourceV1;
use App\Http\Requests\Api\V1\ApiClient\StoreApiClientRequestV1;
use App\Http\Requests\Api\V1\ApiClient\UpdateApiClientRequestV1;
use App\DTOs\V1\ApiClientDTOV1;

class ApiClientControllerV1 extends ApiControllerV1
{
    public function __construct(private ApiClientServiceV1 $apiClientService) {}

    public function index(ApiClientFilter $filters)
    {
        try {
            $perPage = request()->input('per_page', 10);
            $apiClients = $this->apiClientService->getAllApiClients($filters, $perPage);

            return $this->ok('ApiClients retrieved successfully', ApiClientResourceV1::collection($apiClients));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function store(StoreApiClientRequestV1 $request)
    {
        try {
            $dto = ApiClientDTOV1::fromRequest($request->validated()
);
$apiClient = $this->apiClientService->createApiClient($dto);
            return $this->ok('ApiClient created successfully', new ApiClientResourceV1($apiClient));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show(ApiClient $apiClient)
    {
        try {
            return $this->ok('ApiClient retrieved successfully', new ApiClientResourceV1($apiClient));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(UpdateApiClientRequestV1 $request, ApiClient $apiClient)
    {
        try {
            $dto = ApiClientDTOV1::fromRequest($request->validated()
);
$apiClient = $this->apiClientService->updateApiClient($apiClient, $dto);
            return $this->ok('ApiClient updated successfully', new ApiClientResourceV1($apiClient));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(ApiClient $apiClient)
    {
        try {
            $this->apiClientService->deleteApiClient($apiClient);
            return $this->ok('ApiClient deleted successfully');
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}