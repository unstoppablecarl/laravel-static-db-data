<?php

namespace UnstoppableCarl\StaticDBData\Tests\Support\TestData1;

use UnstoppableCarl\StaticDBData\Concerns\SeedsFromStaticData;

class TestData1Seeder
{
    use SeedsFromStaticData;

    public function __construct(TestData1Model $model, TestData1 $staticData)
    {
        $this->staticData = $staticData;
        $this->model = $model;
    }
}
