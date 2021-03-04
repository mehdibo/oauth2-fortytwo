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
            'roles' => [
                [
                    'id' => 1,
                    'name' => 'role_a'
                ],
                [
                    'id' => 1,
                    'name' => 'role_b'
                ]
            ],
            'campus_users' => [
                [
                    "id" => 1,
                    "user_id" => 2,
                    "campus_id" => 3,
                    "is_primary" => false,
                ],
                [
                    "id" => 4,
                    "user_id" => 5,
                    "campus_id" => 6,
                    "is_primary" => true,
                ]
            ]
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

    public function testGetImageUrl(): void
    {
        $this->assertEquals('image_url', $this->owner->getImageUrl());
    }

    public function testGetIsStaff(): void
    {
        $this->assertTrue($this->owner->getIsStaff());
    }

    public function testGetLogin(): void
    {
        $this->assertEquals('ncat', $this->owner->getLogin());
    }

    public function testGetRoles(): void
    {
        $this->assertEquals(['role_a', 'role_b'], $this->owner->getRoles());
    }

    public function testGetPrimaryCampusId(): void
    {
        $this->assertEquals(6, $this->owner->getPrimaryCampusId());
    }
}
