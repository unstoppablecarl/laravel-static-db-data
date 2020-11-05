<?php

namespace UnstoppableCarl\StaticDBData;

use Illuminate\Support\Collection;
use UnstoppableCarl\StaticDBData\Exceptions\StaticDBDataNotFoundException;

abstract class StaticDBData
{
    protected static $cachedData = [];

    protected $primaryKey = 'id';

    public function toArray(): array
    {
        $key = static::class;

        $cached = self::$cachedData[$key] ?? false;
        if (!$cached) {
            self::$cachedData[$key] = $this->prepareData();
        }

        return self::$cachedData[$key];
    }

    public function toCollection(): Collection
    {
        return new Collection($this->toArray());
    }

    public function find($id): ?array
    {
        return $this->toArray()[$id] ?? null;
    }

    public function findOrFail($id): array
    {
        $item = $this->find($id);

        if (!$item) {
            throw new StaticDBDataNotFoundException(static::class, $id, $this->primaryKey);
        }

        return $item;
    }

    public function exists($id): bool
    {
        return $this->find($id) !== null;
    }

    public function primaryKey(): string
    {
        return $this->primaryKey;
    }

    protected function prepareData(): array
    {
        $collection = new Collection($this->data());

        return $collection
            ->keyBy($this->primaryKey)
            ->toArray();
    }

    abstract protected function data(): array;
}
