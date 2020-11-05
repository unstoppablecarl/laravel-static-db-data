<?php

namespace UnstoppableCarl\StaticDBData\Tests\Support\TestData2;

use UnstoppableCarl\StaticDBData\StaticDBData;

class TestData2 extends StaticDBData
{
    protected $primaryKey = '__id';

    public const ALPHA_ID = 'alpha';

    public const BETA_ID = 'beta';

    public const GAMA_ID = 'gama';

    public const DELTA_ID = 'delta';

    protected function data(): array
    {
        return [
            [
                '__id' => self::ALPHA_ID,
                'name' => 'Alpha',
            ],
            [
                '__id' => self::BETA_ID,
                'name' => 'Beta',
            ],
            [
                '__id' => self::GAMA_ID,
                'name' => 'Gama',
            ],
            [
                '__id' => self::DELTA_ID,
                'name' => 'Delta',
            ],
        ];
    }
}
