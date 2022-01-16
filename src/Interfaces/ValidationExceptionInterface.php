<?php

declare(strict_types=1);

namespace Flavacaster\SymfonyHttpBundle\Interfaces;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ValidationExceptionInterface
{
    public function getViolationList(): ConstraintViolationListInterface;
    public function getClass(): string;
    public function getPayload(): array;
}