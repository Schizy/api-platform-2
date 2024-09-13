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
}
