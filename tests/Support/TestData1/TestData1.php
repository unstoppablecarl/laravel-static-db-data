<?php

namespace UnstoppableCarl\StaticDBData\Tests\Support\TestData1;

use UnstoppableCarl\StaticDBData\StaticDBData;

class TestData1 extends StaticDBData
{
    public const ALPHA_ID = 1;

    public const BETA_ID = 2;

    public const GAMA_ID = 3;

    public const DELTA_ID = 4;

    protected function data(): array
    {
        return [
            [
                'id' => self::ALPHA_ID,
                'name' => 'alpha',
            ],
            [
                'id' => self::BETA_ID,
                'name' => 'beta',
            ],
            [
                'id' => self::GAMA_ID,
                'name' => 'gama',
            ],
            [
                'id' => self::DELTA_ID,
                'name' => 'delta',
            ],
        ];
    }
}
