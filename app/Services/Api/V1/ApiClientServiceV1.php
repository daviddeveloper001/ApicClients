<?php

namespace App\Services\Api\V1;

use App\Models\ApiClient;
use Illuminate\Http\Response;
use App\DTOs\V1\ApiClientDTOV1;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ApiClientException;
use App\Repositories\V1\ApiClientRepositoryV1;

class ApiClientServiceV1
{
    //public function __construct(private ApiClientRepositoryV1 $apiClientRepository) {}

    public function __construct(protected ApiClientRepositoryV1 $apiClientRepository)
    {
    }

    public function validateToken(string $plaintextToken, string $ip): ?object
    {
        $clients = $this->apiClientRepository->getActiveClients();

        foreach ($clients as $client) {
            if (
                Hash::check($plaintextToken, $client->token) &&
                (empty($client->ip_whitelist) || in_array($ip, $client->ip_whitelist))
            ) {
                return $client;
            }
        }

        return null;
    }

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