<?php

namespace App\DTOs\V1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiClientDTOV1 implements Arrayable
{
    public $id;
    public $name;
    public $description;
    public $createdAt;
    public $updatedAt;

    protected array $extra = [];

    protected function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->name = $attributes['name'] ?? '';
        $this->description = $attributes['description'] ?? null;
        $this->createdAt = $attributes['created_at'] ?? null;
        $this->updatedAt = $attributes['updated_at'] ?? null;
        $this->extra = array_diff_key($attributes, array_flip(['id', 'name', 'description', 'created_at', 'updated_at']));
    }

    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public static function fromRequest(array $data): self
    {
        $validated = self::validate($data);
        return new self($validated);
    }

    public static function fromModel($model): self
    {
        return new self($model->toArray());
    }

    public static function validate(array $data): array
    {
        $validator = Validator::make($data, self::rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'extra' => $this->extra,
        ];
    }

    public function getExtra(): array
    {
        return $this->extra;
    }
}