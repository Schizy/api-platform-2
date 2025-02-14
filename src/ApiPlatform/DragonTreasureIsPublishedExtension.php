<?php

namespace App\ApiPlatform;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\DragonTreasure;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class DragonTreasureIsPublishedExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $this->andWhereIsPublished($resourceClass, $queryBuilder);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->andWhereIsPublished($resourceClass, $queryBuilder);
    }

    public function andWhereIsPublished(string $resourceClass, QueryBuilder $queryBuilder): void
    {
        if (DragonTreasure::class !== $resourceClass) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];

        if ($user = $this->security->getUser()) {
            $queryBuilder->andWhere(sprintf('%s.isPublished = true OR %s.owner = :owner', $rootAlias, $rootAlias))
                ->setParameter('owner', $user);
        } else {
            $queryBuilder->andWhere(sprintf('%s.isPublished = true', $rootAlias));
        }
    }
}
