<?php

namespace UnstoppableCarl\StaticDBData\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UnstoppableCarl\StaticDBData\Exceptions\StaticDBDataNotFoundException;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData1\TestData1;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData1\TestData1Model;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData1\TestData1Seeder;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData1\TestDataSlug1Model;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData1\TestDataSlug1Seeder;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData1\TestData1SeederWithDelete;

class StaticDBData1Test extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testPrimaryKey()
    {
        $staticDbData = new TestData1();

        $this->assertEquals('id', $staticDbData->primaryKey());
    }

    public function testToArray()
    {
        $staticDbData = new TestData1();

        $expected = $this->expectedData();

        $actual = $staticDbData->toArray();

        $this->assertEquals($expected, $actual);
    }

    public function testToCollection()
    {
        $staticDbData = new TestData1();

        $expected = $this->expectedData();

        $actual = $staticDbData->toCollection();

        $this->assertInstanceOf(Collection::class, $actual);

        $this->assertEquals($expected, $actual->toArray());
    }

    public function testFindAndExists()
    {
        $staticDbData = new TestData1();

        foreach ($this->expectedData() as $id => $expected) {
            $this->assertEquals($expected, $staticDbData->find($id));

            $this->assertEquals(true, $staticDbData->exists($id));
        }

        $this->assertEquals(null, $staticDbData->find(99));
        $this->assertEquals(false, $staticDbData->exists(99));
    }

    public function testFindOrFail()
    {
        $staticDbData = new TestData1();

        foreach ($this->expectedData() as $id => $expected) {
            $this->assertEquals($expected, $staticDbData->findOrFail($id));
        }

        $this->expectException(StaticDBDataNotFoundException::class);
        $this->expectExceptionMessage('id: 99, not found in StaticDBData class: ' . TestData1::class . '.');

        $staticDbData->findOrFail(99);
    }

    public function testSeeder()
    {
        $builder = Mockery::mock(Builder::class);

        foreach ($this->expectedData() as $item) {
            $where = [
                'id' => $item['id'],
            ];

            $builder->shouldReceive('updateOrCreate')
                ->once()
                ->with($where, $item);
        }

        $model = Mockery::mock(TestData1Model::class);
        $model->shouldReceive('query')
            ->andReturn($builder);

        $staticData = new TestData1();
        $seeder = new TestData1Seeder($model, $staticData);

        $seeder->run();
    }

    public function testSeederWithDelete()
    {
        $staticData = new TestData1();

        $builder = Mockery::mock(Builder::class);

        foreach ($this->expectedData() as $item) {
            $where = [
                'id' => $item['id'],
            ];

            $builder->shouldReceive('updateOrCreate')
                ->once()
                ->with($where, $item);
        }

        $ids = array_keys($this->expectedData());
        $builder->shouldReceive('whereNotIn')
            ->once()
            ->with($staticData->primaryKey(), $ids)
            ->andReturn($builder);

        $builder->shouldReceive('whereNotIn->delete')
            ->once();

        $model = Mockery::mock(TestData1Model::class);
        $model->shouldReceive('query')
            ->andReturn($builder);

        $seeder = new TestData1SeederWithDelete($model, $staticData);

        $seeder->run();
    }

    private function expectedData(): array
    {
        return [
            1 => [
                'id' => 1,
                'name' => 'alpha',
            ],
            2 => [
                'id' => 2,
                'name' => 'beta',
            ],
            3 => [
                'id' => 3,
                'name' => 'gama',
            ],
            4 => [
                'id' => 4,
                'name' => 'delta',
            ],
        ];
    }
}
