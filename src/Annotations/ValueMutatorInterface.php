<?php

declare(strict_types=1);

namespace Flavacaster\SymfonyHttpBundle\Annotations;

/**
 * @Annotation
 */
interface ValueMutatorInterface
{
    /**
     * @param mixed $value
     * @param array $payload
     * @param string $property
     * @return mixed
     */
    public function mutate($value, array $payload, string $property);
}
