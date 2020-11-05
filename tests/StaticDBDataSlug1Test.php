<?php

namespace UnstoppableCarl\StaticDBData\Tests;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use UnstoppableCarl\StaticDBData\Exceptions\StaticDBDataNotFoundException;
use UnstoppableCarl\StaticDBData\Tests\Support\TestDataSlug1;

class StaticDBDataSlug1Test extends TestCase
{
    public function testPrimaryKey()
    {
        $staticDbData = new TestDataSlug1();

        $this->assertEquals('id', $staticDbData->primaryKey());
    }

    public function testToArray()
    {
        $staticDbData = new TestDataSlug1();

        $expected = $this->expectedData();

        $actual = $staticDbData->toArray();

        $this->assertEquals($expected, $actual);
    }

    public function testToCollection()
    {
        $staticDbData = new TestDataSlug1();

        $expected = $this->expectedData();

        $actual = $staticDbData->toCollection();

        $this->assertInstanceOf(Collection::class, $actual);

        $this->assertEquals($expected, $actual->toArray());
    }

    public function testFindAndExists()
    {
        $staticDbData = new TestDataSlug1();

        foreach ($this->expectedData() as $id => $expected) {
            $this->assertEquals($expected, $staticDbData->find($id));

            $this->assertEquals(true, $staticDbData->exists($id));
        }

        $this->assertEquals(null, $staticDbData->find(99));
        $this->assertEquals(false, $staticDbData->exists(99));
    }

    public function testSlugExists()
    {
        $staticDbData = new TestDataSlug1();

        foreach ($this->expectedData() as $id => $expected) {
            $slug = $expected['slug'];

            $this->assertEquals(true, $staticDbData->slugExists($slug));
        }

        $this->assertEquals(false, $staticDbData->slugExists('invalid_slug'));
    }

    public function testFindOrFail()
    {
        $staticDbData = new TestDataSlug1();

        foreach ($this->expectedData() as $id => $expected) {
            $this->assertEquals($expected, $staticDbData->findOrFail($id));
        }

        $this->expectException(StaticDBDataNotFoundException::class);
        $this->expectExceptionMessage('id: 99, not found in StaticDBData class: ' . TestDataSlug1::class . '.');

        $staticDbData->findOrFail(99);
    }

    public function testIdToSlug()
    {
        $staticDbData = new TestDataSlug1();

        foreach ($this->expectedData() as $id => $expected) {
            $expectedSlug = $expected['slug'];
            $this->assertEquals($expectedSlug, $staticDbData->idToSlug($id));
        }

        $this->assertEquals(null, $staticDbData->idToSlug(99));
    }

    public function testIdToSlugOrFail()
    {
        $staticDbData = new TestDataSlug1();

        foreach ($this->expectedData() as $id => $expected) {
            $expectedSlug = $expected['slug'];
            $this->assertEquals($expectedSlug, $staticDbData->idToSlugOrFail($id));
        }

        $this->expectException(StaticDBDataNotFoundException::class);
        $this->expectExceptionMessage('id: 99, not found in StaticDBData class: ' . TestDataSlug1::class . '.');

        $staticDbData->idToSlugOrFail(99);
    }

    public function testSlugToId()
    {
        $staticDbData = new TestDataSlug1();

        foreach ($this->expectedData() as $expectedId => $expected) {
            $slug = $expected['slug'];
            $this->assertEquals($expectedId, $staticDbData->slugToId($slug));
        }

        $this->assertEquals(null, $staticDbData->slugToId('invalid_slug'));
    }

    public function testSlugToIdOrFail()
    {
        $staticDbData = new TestDataSlug1();

        foreach ($this->expectedData() as $expectedId => $expected) {
            $slug = $expected['slug'];
            $this->assertEquals($expectedId, $staticDbData->slugToIdOrFail($slug));
        }

        $this->expectException(StaticDBDataNotFoundException::class);
        $message = 'slug: invalid_slug, not found in StaticDBData class: ' . TestDataSlug1::class . '.';
        $this->expectExceptionMessage($message);

        $staticDbData->slugToIdOrFail('invalid_slug');
    }

    private function expectedData(): array
    {
        return [
            1 => [
                'id' => 1,
                'slug' => 'alpha',
                'name' => 'Alpha',
            ],
            2 => [
                'id' => 2,
                'slug' => 'beta',
                'name' => 'Beta',
            ],
            3 => [
                'id' => 3,
                'slug' => 'gama',
                'name' => 'Gama',
            ],
            4 => [
                'id' => 4,
                'slug' => 'delta',
                'name' => 'Delta',
            ],
        ];
    }
}
