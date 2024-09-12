<?php

namespace App\Tests\Functional;

use App\Factory\DragonTreasureFactory;
use App\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Response;

class DragonTreasureResourceTest extends ApiTestCase
{
    public function testGetCollectionOfTreasures(): void
    {
        DragonTreasureFactory::createMany(5);

        $json = $this->browser()
            ->get('/api/treasures')
            ->assertJson()
            ->assertJsonMatches('"hydra:totalItems"', 5)
            ->assertJsonMatches('length("hydra:member")', 5)
            ->json();

        $this->assertSame(array_keys($json->decoded()['hydra:member'][0]), [
            '@id', '@type', 'name', 'description', 'value', 'coolFactor', 'owner', 'shortDescription', 'plunderedAtAgo',
        ]);
    }

    public function testPostToCreateTreasure(): void
    {
        $user = UserFactory::createOne();

        $this->browser()
            ->actingAs($user)
            ->post('/api/treasures', [
                'json' => []
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->post('/api/treasures', [
                'json' => [
                    'name' => 'A shiny thing',
                    'description' => 'It sparkles when I wave it in the air.',
                    'value' => 1000,
                    'coolFactor' => 5,
                    'owner' => '/api/users/' . $user->getId(),
                ],
            ])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonMatches('name', 'A shiny thing');
    }
}
