<?php

namespace UnstoppableCarl\StaticDBData\Tests\Support\TestData1;

class TestData1SeederWithDelete extends TestData1Seeder
{
    protected $deleteNonMatchingIds = true;
}
