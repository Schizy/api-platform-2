<?php

namespace App\DataFixtures;

use App\Factory\ApiTokenFactory;
use App\Factory\DragonTreasureFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'bernie@dragonmail.com',
            'password' => 'roar',
        ]);

        UserFactory::createMany(10);
        DragonTreasureFactory::createMany(40, static fn() => ['owner' => UserFactory::random(), 'isPublished' => random_int(0, 10) > 3,]);
        ApiTokenFactory::createMany(30, static fn() => ['ownedBy' => UserFactory::random()]);
    }
}
