<?php

namespace UnstoppableCarl\StaticDBData\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UnstoppableCarl\StaticDBData\Exceptions\StaticDBDataNotFoundException;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData2\TestData2;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData2\TestData2Model;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData2\TestData2Seeder;
use UnstoppableCarl\StaticDBData\Tests\Support\TestData2\TestData2SeederWithDelete;

class StaticDBData2Test extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testPrimaryKey()
    {
        $staticDbData = new TestData2();

        $this->assertEquals('__id', $staticDbData->primaryKey());
    }

    public function testToArray()
    {
        $staticDbData = new TestData2();

        $expected = $this->expectedData();

        $actual = $staticDbData->toArray();

        $this->assertEquals($expected, $actual);
    }

    public function testToCollection()
    {
        $staticDbData = new TestData2();

        $expected = $this->expectedData();

        $actual = $staticDbData->toCollection();

        $this->assertInstanceOf(Collection::class, $actual);

        $this->assertEquals($expected, $actual->toArray());
    }

    public function testFindAndExists()
    {
        $staticDbData = new TestData2();

        foreach ($this->expectedData() as $id => $expected) {
            $this->assertEquals($expected, $staticDbData->find($id));

            $this->assertEquals(true, $staticDbData->exists($id));
        }

        $this->assertEquals(null, $staticDbData->find(99));
        $this->assertEquals(false, $staticDbData->exists(99));
    }

    public function testFindOrFail()
    {
        $staticDbData = new TestData2();

        foreach ($this->expectedData() as $id => $expected) {
            $this->assertEquals($expected, $staticDbData->findOrFail($id));
        }

        $this->expectException(StaticDBDataNotFoundException::class);
        $this->expectExceptionMessage('id: 99, not found in StaticDBData class: ' . TestData2::class . '.');

        $staticDbData->findOrFail(99);
    }

    public function testSeeder()
    {
        $builder = Mockery::mock(Builder::class);

        foreach ($this->expectedData() as $item) {
            $where = [
                '__id' => $item['__id'],
            ];

            $builder->shouldReceive('updateOrCreate')
                ->once()
                ->with($where, $item);
        }

        $model = Mockery::mock(TestData2Model::class);
        $model->shouldReceive('query')
            ->andReturn($builder);

        $staticData = new TestData2();
        $seeder = new TestData2Seeder($model, $staticData);

        $seeder->run();
    }

    public function testSeederWithDelete()
    {
        $staticData = new TestData2();

        $builder = Mockery::mock(Builder::class);

        foreach ($this->expectedData() as $item) {
            $where = [
                '__id' => $item['__id'],
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

        $model = Mockery::mock(TestData2Model::class);
        $model->shouldReceive('query')
            ->andReturn($builder);

        $seeder = new TestData2SeederWithDelete($model, $staticData);

        $seeder->run();
    }

    private function expectedData(): array
    {
        return [
            'alpha' => [
                '__id' => 'alpha',
                'name' => 'Alpha',
            ],
            'beta' => [
                '__id' => 'beta',
                'name' => 'Beta',
            ],
            'gama' => [
                '__id' => 'gama',
                'name' => 'Gama',
            ],
            'delta' => [
                '__id' => 'delta',
                'name' => 'Delta',
            ],
        ];
    }
}
