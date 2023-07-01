<?php

namespace App\Support\Normalizers;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CarbonImmutableNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        if (! $object instanceof CarbonImmutable) {
            throw new InvalidArgumentException('Cannot serialize an object that is not a CarbonImmutable in CarbonImmutableNormalizer.');
        }

        return $object->toRfc3339String();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof CarbonImmutable;
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $class, string $format = null, array $context = []): CarbonImmutable
    {
        return CarbonImmutable::parse($data);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return is_a($type, CarbonImmutable::class, true);
    }
}
