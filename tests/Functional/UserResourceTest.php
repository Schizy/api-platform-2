<?php

namespace Functional;

use App\Factory\DragonTreasureFactory;
use App\Factory\UserFactory;
use App\Tests\Functional\ApiTestCase;

class UserResourceTest extends ApiTestCase
{
    public function testPostToCreateUser(): void
    {
        $this->browser()
            ->post('/api/users', [
                'json' => [
                    'username' => "I'm a dragon",
                    'email' => 'test@test.com',
                    'password' => 'password',
                ]
            ])
            ->assertStatus(201)
            ->post('/login', [
                'json' => [
                    'email' => 'test@test.com',
                    'password' => 'password',
                ]
            ])
            ->assertSuccessful();
    }

    public function testPatchToUpdateUser(): void
    {
        $user = UserFactory::createOne();

        $this->browser()
            ->actingAs($user)
            ->patchWithHeader('/api/users/' . $user->getId(), [
                'json' => [
                    'username' => "I'm a NEW dragon now",
                ]
            ])
            ->assertStatus(200);
    }

    public function testTreasuresCannotBeStolen(): void
    {
        $randomDragon = UserFactory::createOne();
        $hisTreasure = DragonTreasureFactory::createOne(['owner' => $randomDragon]);
        $definitelyNotHisTreasure = DragonTreasureFactory::createOne();

        $this->browser()
            ->actingAs($randomDragon)
            ->patchWithHeader('/api/users/' . $randomDragon->getId(), [
                'json' => [
                    'username' => 'changed',
                    'dragonTreasures' => [
                        '/api/treasures/' . $hisTreasure->getId(),
                        '/api/treasures/' . $definitelyNotHisTreasure->getId(),
                    ],
                ]
            ])
            ->assertStatus(422);
    }
}

