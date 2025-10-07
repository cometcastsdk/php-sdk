<?php

namespace Cometcast\Openapi\Tests\HttpClient;

use Cometcast\Openapi\HttpClient\Result;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cometcast\Openapi\HttpClient\Result
 */
class ResultTest extends TestCase
{
    public function testImplementsInterfaces(): void
    {
        $result = new Result();
        $this->assertInstanceOf(\ArrayAccess::class, $result);
        $this->assertInstanceOf(\IteratorAggregate::class, $result);
        $this->assertInstanceOf(\Countable::class, $result);
        $this->assertSame(0, count($result));
    }

    public function testArrayAccessSetAndGet(): void
    {
        $result = new Result();
        $result['foo'] = 'bar';
        $result['num'] = 123;

        $this->assertSame('bar', $result['foo']);
        $this->assertSame(123, $result['num']);
    }

    public function testGetReturnsNullForMissingKey(): void
    {
        $result = new Result();
        $this->assertNull($result['missing']);

        $result['present'] = 'value';
        $this->assertNull($result['other']);
        $this->assertSame('value', $result['present']);
    }

    public function testUnsetRemovesKey(): void
    {
        $result = new Result();
        $result['k'] = 'v';
        $this->assertSame('v', $result['k']);

        unset($result['k']);
        $this->assertNull($result['k']);
    }

    public function testIteratorReturnsAllItems(): void
    {
        $result = new Result();
        $result['a'] = 1;
        $result['b'] = 2;
        $result['c'] = 3;

        $collected = [];
        foreach ($result as $key => $value) {
            $collected[$key] = $value;
        }

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $collected);
    }

    public function testCountReflectsNumberOfItems(): void
    {
        $result = new Result();
        $this->assertSame(0, count($result));

        $result['x'] = 'X';
        $result['y'] = 'Y';
        $this->assertSame(2, count($result));

        unset($result['x']);
        $this->assertSame(1, count($result));
    }

    public function testSupportsVariousValueTypes(): void
    {
        $result = new Result();
        $result['str'] = 'text';
        $result['int'] = 42;
        $result['float'] = 3.14;
        $result['bool'] = true;
        $result['arr'] = ['k' => 'v'];
        $result['null'] = null;

        $this->assertSame('text', $result['str']);
        $this->assertSame(42, $result['int']);
        $this->assertSame(3.14, $result['float']);
        $this->assertTrue($result['bool']);
        $this->assertSame(['k' => 'v'], $result['arr']);
        $this->assertNull($result['null']);
    }
}