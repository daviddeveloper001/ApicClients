<?php

namespace App\Models;

class Customer extends ModelBase
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'document_number',
        'deleted_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}