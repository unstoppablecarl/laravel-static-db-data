<?php

namespace UnstoppableCarl\StaticDBData\Tests\Support\TestData2;

use UnstoppableCarl\StaticDBData\Concerns\SeedsFromStaticData;

class TestData2Seeder
{
    use SeedsFromStaticData;

    public function __construct(TestData2Model $model, TestData2 $staticData)
    {
        $this->staticData = $staticData;
        $this->model = $model;
    }
}
