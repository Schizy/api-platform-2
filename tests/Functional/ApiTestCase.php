<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class ApiTestCase extends KernelTestCase
{
    use ResetDatabase;
    use Factories;
    use HasBrowser {
        browser as baseBrowserMethod;
    }

    protected function browser(array $options = [], array $server = []): KernelBrowser
    {
        return $this->baseBrowserMethod($options, $server)
            ->setDefaultHttpOptions(HttpOptions::create()->withHeader('Accept', 'application/ld+json'));
    }
}
