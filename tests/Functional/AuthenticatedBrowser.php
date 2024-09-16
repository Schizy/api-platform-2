<?php

namespace App\Tests\Functional;

use App\Entity\ApiToken;
use App\Factory\ApiTokenFactory;
use Zenstruck\Browser\KernelBrowser;

class AuthenticatedBrowser extends KernelBrowser
{
    public function withFullUser(): self
    {
        return $this->authenticate([
            ApiToken::SCOPE_USER_EDIT,
            ApiToken::SCOPE_TREASURE_CREATE,
            ApiToken::SCOPE_TREASURE_EDIT,
        ]);
    }

    public function withRestrictedUser(array $scopes = []): self
    {
        return $this->authenticate($scopes);
    }

    private function authenticate(array $scopes): self
    {
        $token = ApiTokenFactory::createOne(['scopes' => $scopes]);

        return $this->setDefaultHttpOptions(['headers' => ['Authorization' => 'Bearer ' . $token->getToken()]]);
    }

    public function patchWithHeader(string $url, array $options): self
    {
        // ApiPlatform requires the merge-patch header for patch queries
        // We didn't need it in DragonTreasure because of the formats key in the resource
        return $this
            ->setDefaultHttpOptions(['headers' => ['Content-Type' => 'application/merge-patch+json']])
            ->patch($url, $options);
    }
}
