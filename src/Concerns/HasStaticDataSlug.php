<?php

namespace UnstoppableCarl\StaticDBData\Concerns;

use UnstoppableCarl\StaticDBData\Exceptions\StaticDBDataNotFoundException;

trait HasStaticDataSlug
{
    public function idToSlugOrFail(int $id): string
    {
        $slug = $this->idToSlug($id);

        if (!$slug) {
            throw new StaticDBDataNotFoundException(static::class, $id, $this->primaryKey());
        }

        return $slug;
    }

    public function slugToIdOrFail(string $slug): int
    {
        $id = $this->slugToId($slug);

        if (!$id) {
            throw new StaticDBDataNotFoundException(static::class, $slug, $this->slugKey());
        }

        return $id;
    }

    public function idToSlug(int $id): ?string
    {
        return $this->toArray()[$id][$this->slugKey()] ?? null;
    }

    public function slugToId(string $slug): ?int
    {
        $value = $this->bySlug($slug);

        return $value['id'] ?? null;
    }

    public function slugExists(string $slug): bool
    {
        return (bool) $this->toCollection()->firstWhere($this->slugKey(), $slug);
    }

    protected function bySlug(string $slug): ?array
    {
        return collect($this->toArray())
            ->firstWhere($this->slugKey(), '=', $slug);
    }

    protected function slugKey(): string
    {
        if (property_exists($this, 'slugKey')) {
            return $this->slugKey;
        }

        return 'slug';
    }
}
