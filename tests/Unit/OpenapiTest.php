<?php

namespace Cometcast\Openapi\Tests\Unit;

use Cometcast\Openapi\Openapi;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cometcast\Openapi\Openapi
 */
class OpenapiTest extends TestCase
{
    /**
     * @covers \Cometcast\Openapi\Openapi::__construct
     */
    public function testCanBeInstantiated(): void
    {
        $openapi = new Openapi();
        
        $this->assertInstanceOf(Openapi::class, $openapi);
    }
    
    /**
     * @covers \Cometcast\Openapi\Openapi::getVersion
     */
    public function testGetVersion(): void
    {
        $openapi = new Openapi();
        
        $this->assertEquals('1.0.0', $openapi->getVersion());
    }
}