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
            'pool_month' => 'august',
            'pool_year' => '2020',
            'correction_point' => 5,
            'wallet' => 1337,
            'anonymize_date' => '2022-12-09T00:00:00.000+01:00',
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
            ],
            'cursus_users' => [
                [
                    'grade' => 'Commander',
                    'level' => 13.37,
                    'skills' => [],
                    'blackholed_at' => null,
                    'id' => 3213,
                    'cursus' => [
                        'id' => 2,
                        'name' => 'Test cursus',
                    ],
                ],
                [
                    'grade' => 'Chandler',
                    'level' => 42.20,
                    'skills' => [],
                    'blackholed_at' => null,
                    'id' => 4222,
                    'cursus' => [
                        'id' => 5,
                        'name' => 'Test cursus 2',
                    ],
                ]
            ],
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

    public function testGetEvaluationPoints(): void
    {
        $this->assertEquals(5, $this->owner->getEvaluationPoints());
    }

    public function testGetPoolMonth(): void
    {
        $this->assertEquals('august', $this->owner->getPoolMonth());
    }

    public function testGetPoolYear(): void
    {
        $this->assertEquals('2020', $this->owner->getPoolYear());
    }

    public function testGetWallet(): void
    {
        $this->assertEquals(1337, $this->owner->getWallet());
    }

    public function testGetAnonymizationDate(): void
    {
        $expectedDate = new \DateTime('2022-12-09T00:00:00.000+01:00');
        $this->assertEquals($expectedDate, $this->owner->getAnonymizationDate());
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

    public function testGetCursusUsers(): void
    {
        $data = [
            [
                'grade' => 'Commander',
                'level' => 13.37,
                'skills' => [],
                'blackholed_at' => null,
                'id' => 3213,
                'cursus' => [
                    'id' => 2,
                    'name' => 'Test cursus',
                ],
            ],
            [
                'grade' => 'Chandler',
                'level' => 42.20,
                'skills' => [],
                'blackholed_at' => null,
                'id' => 4222,
                'cursus' => [
                    'id' => 5,
                    'name' => 'Test cursus 2',
                ],
            ]
        ];
        $this->assertEquals($data, $this->owner->getCursusUsers());
    }

    public function testGetCursusUser(): void
    {
        $cursusUser = $this->owner->getCursusUser(5);
        $this->assertNotNull($cursusUser);
        // For PHPSta
        if ($cursusUser === null) {
            return;
        }
        $this->assertArrayHasKey('grade', $cursusUser);
        $this->assertEquals('Chandler', $cursusUser['grade']);
    }
}
