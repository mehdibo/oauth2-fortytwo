<?php

namespace Mehdibo\OAuth2\Client\Test;

use Mehdibo\OAuth2\Client\Provider\ResourceOwner;
use PHPUnit\Framework\TestCase;

class ResourceOwnerTest extends TestCase
{

    private ResourceOwner $owner;

    protected function setUp(): void
    {
        $this->owner = new ResourceOwner([
            'id' => 42,
            'login' => 'ncat',
            'email' => 'test@email.com',
            'first_name' => 'Norminet',
            'last_name' => 'Cat',
            'image_url' => 'image_url',
            'staff?' => true,

        ]);
    }

    public function testGetId(): void
    {
        $this->assertEquals(42, $this->owner->getId());
    }

    public function testGetEmail(): void
    {
        $this->assertEquals('test@email.com', $this->owner->getEmail());
    }

    public function testGetFirstName(): void
    {
        $this->assertEquals('Norminet', $this->owner->getFirstName());
    }

    public function testGetLastName(): void
    {
        $this->assertEquals('Cat', $this->owner->getLastName());
    }

    public function testGetImageUrl()
    {
        $this->assertEquals('image_url', $this->owner->getImageUrl());
    }

    public function testGetIsStaff()
    {
        $this->assertTrue($this->owner->getIsStaff());
    }

    public function testGetLogin()
    {
        $this->assertEquals('ncat', $this->owner->getLogin());
    }
}
