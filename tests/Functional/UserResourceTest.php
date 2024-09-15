<?php

namespace Functional;

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
}
