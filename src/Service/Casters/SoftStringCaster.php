<?php

declare(strict_types=1);

namespace Flavacaster\SymfonyHttpBundle\Service\Casters;

final class SoftStringCaster implements SoftCasterInterface
{
    public function cast($value)
    {
        if (! is_scalar($value)) {
            return $value;
        }

        return (string) $value;
    }
}
