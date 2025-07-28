<?php

namespace App\Services\Api\V1;

use App\Models\ApiClient;
use App\Exceptions\ApiClientException;
use App\Repositories\V1\ApiClientRepositoryV1;
use Illuminate\Http\Response;
use App\DTOs\V1\ApiClientDTOV1;

class ApiClientServiceV1
{
    public function __construct(private ApiClientRepositoryV1 $apiClientRepository) {}

    public function getAllApiClients($filters, $perPage)
    {
        try {
            return ApiClient::filter($filters)->paginate($perPage);
        } catch (\Exception $e) {
            throw new ApiClientException(
                'Failed to retrieve ApiClients',
                developerHint: $e->getMessage(),
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                previous: $e
            );
        }
    }

    public function getApiClientById(ApiClient $apiClient)
    {
        try {
            $result = $this->apiClientRepository->find($apiClient);
            if (!$result) {
                throw new ApiClientException('ApiClient not found', Response::HTTP_NOT_FOUND);
            }
            return $result;
        } catch (\Exception $e) {
            throw new ApiClientException(
                'Failed to retrieve ApiClient',
                developerHint: $e->getMessage(),
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                previous: $e
            );
        }
    }

    public function createApiClient(ApiClientDTOV1 $dto)
{
    return $this->apiClientRepository->create($dto->toArray());
}

    public function updateApiClient(ApiClient $apiClient, ApiClientDTOV1 $dto)
{
    return $this->apiClientRepository->update($apiClient, $dto->toArray());
}

    public function deleteApiClient(ApiClient $apiClient)
    {
        try {
            return $this->apiClientRepository->delete($apiClient);
        } catch (\Exception $e) {
            throw new ApiClientException(
                'Failed to delete ApiClient',
                developerHint: $e->getMessage(),
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                previous: $e
            );
        }
    }
}