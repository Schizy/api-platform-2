<?php

namespace App\ApiPlatform;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\SecurityScheme;
use ApiPlatform\OpenApi\OpenApi;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator('api_platform.openapi.factory')]
class OpenApiFactoryDecorator implements OpenApiFactoryInterface
{
    public function __construct(private readonly OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        if ($securitySchemes = $openApi->getComponents()->getSecuritySchemes()) {
            $securitySchemes['access_token'] = new SecurityScheme(type: 'http', scheme: 'bearer');
        }

        return $openApi;
    }
}
