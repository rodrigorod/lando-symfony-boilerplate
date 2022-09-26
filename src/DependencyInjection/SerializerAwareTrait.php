<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * Trait SerializerAwareTrait.
 */
trait SerializerAwareTrait
{
    /**
     * Serializer.
     */
    protected SerializerInterface $serializer;

    /**
     * Set serializer.
     *
     * @param SerializerInterface $serializer
     *   Serializer
     *
     * @required
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }
}
