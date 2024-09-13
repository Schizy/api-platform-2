<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class ApiTestCase extends KernelTestCase
{
    use ResetDatabase;
    use Factories;
    use HasBrowser {
        browser as authenticatedBrowser;
    }

    protected function browser(array $options = [], array $server = []): AuthenticatedBrowser
    {
        return $this->authenticatedBrowser($options, $server)
            ->setDefaultHttpOptions(['headers' => ['Accept', 'application/ld+json']]);
    }
}
