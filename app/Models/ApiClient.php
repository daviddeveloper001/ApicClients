<?php

namespace App\Models;

class ApiClient extends ModelBase
{
    protected $table = 'api_clients';

    protected $fillable = [
        'name',
        'token',
        'permissions',
        'ip_whitelist',
        'active',
        'last_connected_at',
        'deleted_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'ip_whitelist' => 'array',
        'active' => 'boolean',
        'last_connected_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}