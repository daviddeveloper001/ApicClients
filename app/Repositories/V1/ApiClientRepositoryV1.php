<?php

namespace App\Repositories\V1;

use App\Models\ApiClient;
use App\Repositories\V1\BaseRepositoryV1;

class ApiClientRepositoryV1 extends BaseRepositoryV1
{
    const RELATIONS = [];

    public function __construct(ApiClient $apiClient)
    {
        parent::__construct($apiClient, self::RELATIONS);
    }
}