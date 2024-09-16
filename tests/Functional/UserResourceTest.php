<?php

namespace Functional;

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
}
