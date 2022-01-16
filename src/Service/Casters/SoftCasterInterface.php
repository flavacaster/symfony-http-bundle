<?php

declare(strict_types=1);

namespace Flavacaster\SymfonyHttpBundle\Service\Casters;

interface SoftCasterInterface
{
    public function cast($value);
}
