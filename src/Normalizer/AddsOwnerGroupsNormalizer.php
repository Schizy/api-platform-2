<?php

namespace App\Normalizer;

use App\Entity\DragonTreasure;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsDecorator('api_platform.jsonld.normalizer.item')]
class AddsOwnerGroupsNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    public function __construct(private readonly NormalizerInterface $normalizer, private readonly Security $security)
    {
    }

    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $isMine = $object instanceof DragonTreasure && $this->security->getUser() === $object->getOwner();

        if ($isMine) {
            $context['groups'][] = 'owner:read'; // We can add groups on the fly
        }

        $normalized = $this->normalizer->normalize($object, $format, $context);

        if ($isMine) {
            $normalized['isMine'] = true; // We can add or modify data even after the normalization
        }

        return $normalized;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $this->normalizer->supportsNormalization($data, $format, $context);
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        if ($this->normalizer instanceof SerializerAwareInterface) {
            $this->normalizer->setSerializer($serializer);
        }
    }
}
