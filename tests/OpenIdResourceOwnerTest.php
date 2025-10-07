<?php

namespace Cometcast\Openapi\Tests;

use Cometcast\Openapi\OpenIdResourceOwner;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Cometcast\Openapi\OpenIdResourceOwner
 */
class OpenIdResourceOwnerTest extends TestCase
{
    private $sampleResponse = [
        'sub' => '12345',
        'email' => 'john@example.com',
        'name' => 'John Doe',
        'preferred_username' => 'johndoe',
        'given_name' => 'John',
        'family_name' => 'Doe'
    ];

    public function testConstructorWithResponse(): void
    {
        $owner = new OpenIdResourceOwner($this->sampleResponse);
        $this->assertEquals($this->sampleResponse, $owner->toArray());
    }

    public function testConstructorWithEmptyResponse(): void
    {
        $owner = new OpenIdResourceOwner();
        $this->assertEquals([], $owner->toArray());
    }

    public function testGetId(): void
    {
        $owner = new OpenIdResourceOwner($this->sampleResponse);
        $this->assertEquals('12345', $owner->getId());
    }

    public function testGetIdWhenNotPresent(): void
    {
        $owner = new OpenIdResourceOwner([]);
        $this->assertNull($owner->getId());
    }

    public function testGetEmail(): void
    {
        $owner = new OpenIdResourceOwner($this->sampleResponse);
        $this->assertEquals('john@example.com', $owner->getEmail());
    }

    public function testGetEmailWhenNotPresent(): void
    {
        $owner = new OpenIdResourceOwner([]);
        $this->assertNull($owner->getEmail());
    }

    public function testGetName(): void
    {
        $owner = new OpenIdResourceOwner($this->sampleResponse);
        $this->assertEquals('John Doe', $owner->getName());
    }

    public function testGetNameWhenNotPresent(): void
    {
        $owner = new OpenIdResourceOwner([]);
        $this->assertNull($owner->getName());
    }

    public function testGetUsername(): void
    {
        $owner = new OpenIdResourceOwner($this->sampleResponse);
        $this->assertEquals('johndoe', $owner->getUsername());
    }

    public function testGetUsernameWhenNotPresent(): void
    {
        $owner = new OpenIdResourceOwner([]);
        $this->assertNull($owner->getUsername());
    }

    public function testGetFirstName(): void
    {
        $owner = new OpenIdResourceOwner($this->sampleResponse);
        $this->assertEquals('John', $owner->getFirstName());
    }

    public function testGetFirstNameWhenNotPresent(): void
    {
        $owner = new OpenIdResourceOwner([]);
        $this->assertNull($owner->getFirstName());
    }

    public function testGetLastName(): void
    {
        $owner = new OpenIdResourceOwner($this->sampleResponse);
        $this->assertEquals('Doe', $owner->getLastName());
    }

    public function testGetLastNameWhenNotPresent(): void
    {
        $owner = new OpenIdResourceOwner([]);
        $this->assertNull($owner->getLastName());
    }

    public function testToArray(): void
    {
        $owner = new OpenIdResourceOwner($this->sampleResponse);
        $this->assertEquals($this->sampleResponse, $owner->toArray());
    }

    public function testPartialResponse(): void
    {
        $partialResponse = [
            'sub' => '67890',
            'email' => 'jane@example.com'
        ];

        $owner = new OpenIdResourceOwner($partialResponse);

        $this->assertEquals('67890', $owner->getId());
        $this->assertEquals('jane@example.com', $owner->getEmail());
        $this->assertNull($owner->getName());
        $this->assertNull($owner->getUsername());
        $this->assertNull($owner->getFirstName());
        $this->assertNull($owner->getLastName());
        $this->assertEquals($partialResponse, $owner->toArray());
    }

    public function testWithNullValues(): void
    {
        $responseWithNulls = [
            'sub' => null,
            'email' => null,
            'name' => null,
            'preferred_username' => null,
            'given_name' => null,
            'family_name' => null
        ];

        $owner = new OpenIdResourceOwner($responseWithNulls);

        $this->assertNull($owner->getId());
        $this->assertNull($owner->getEmail());
        $this->assertNull($owner->getName());
        $this->assertNull($owner->getUsername());
        $this->assertNull($owner->getFirstName());
        $this->assertNull($owner->getLastName());
        $this->assertEquals($responseWithNulls, $owner->toArray());
    }

    public function testWithEmptyStringValues(): void
    {
        $responseWithEmptyStrings = [
            'sub' => '',
            'email' => '',
            'name' => '',
            'preferred_username' => '',
            'given_name' => '',
            'family_name' => ''
        ];

        $owner = new OpenIdResourceOwner($responseWithEmptyStrings);

        $this->assertEquals('', $owner->getId());
        $this->assertEquals('', $owner->getEmail());
        $this->assertEquals('', $owner->getName());
        $this->assertEquals('', $owner->getUsername());
        $this->assertEquals('', $owner->getFirstName());
        $this->assertEquals('', $owner->getLastName());
        $this->assertEquals($responseWithEmptyStrings, $owner->toArray());
    }
}