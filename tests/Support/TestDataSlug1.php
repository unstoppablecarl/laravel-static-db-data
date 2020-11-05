<?php

namespace UnstoppableCarl\StaticDBData\Tests\Support;

use UnstoppableCarl\StaticDBData\Concerns\HasStaticDataSlug;
use UnstoppableCarl\StaticDBData\StaticDBData;

class TestDataSlug1 extends StaticDBData
{
    use HasStaticDataSlug;

    public const ALPHA_ID = 1;
    public const ALPHA = 'alpha';

    public const BETA_ID = 2;
    public const BETA = 'beta';

    public const GAMA_ID = 3;
    public const GAMA = 'gama';

    public const DELTA_ID = 4;
    public const DELTA = 'delta';

    protected function data(): array
    {
        return [
            [
                'id' => self::ALPHA_ID,
                'slug' => self::ALPHA,
                'name' => 'Alpha',
            ],
            [
                'id' => self::BETA_ID,
                'slug' => self::BETA,
                'name' => 'Beta',
            ],
            [
                'id' => self::GAMA_ID,
                'slug' => self::GAMA,
                'name' => 'Gama',
            ],
            [
                'id' => self::DELTA_ID,
                'slug' => self::DELTA,
                'name' => 'Delta',
            ],
        ];
    }
}
