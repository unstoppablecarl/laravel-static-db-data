<?php

namespace UnstoppableCarl\StaticDBData\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnstoppableCarl\StaticDBData\StaticDBData;

trait SeedsFromStaticData
{
    /** @var StaticDBData */
    protected $staticData;

    /** @var Model */
    protected $model;

    public function run()
    {
        $data = $this->prepareStaticData();
        $this->seedData($data);

        if ($this->shouldDeleteNonMatchingIds()) {
            $this->deleteNonMatchingIds($data);
        }
    }

    protected function seedData(array $data)
    {
        foreach ($data as $item) {
            $this->updateOrCreate($item);
        }
    }

    protected function updateOrCreate(array $item)
    {
        $where = $this->seedDataWhere($item);

        $this->modelQuery()->updateOrCreate($where, $item);
    }

    protected function seedDataWhere(array $item): array
    {
        $primaryKey = $this->staticData->primaryKey();

        return [
            $primaryKey => $item[$primaryKey],
        ];
    }

    protected function deleteNonMatchingIds(array $data)
    {
        $primaryKey = $this->staticData->primaryKey();

        $ids = array_column($data, $primaryKey);

        $this->modelQuery()
            ->whereNotIn($primaryKey, $ids)
            ->delete();
    }

    protected function prepareStaticData(): array
    {
        return $this->staticData->toArray();
    }

    protected function modelQuery(): Builder
    {
        return $this->model->query();
    }

    protected function shouldDeleteNonMatchingIds(): bool
    {
        if (property_exists($this, 'deleteNonMatchingIds')) {
            return $this->deleteNonMatchingIds;
        }

        return false;
    }
}
