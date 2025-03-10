<?php

declare(strict_types=1);

namespace Flavacaster\SymfonyHttpBundle\Exceptions;

use Flavacaster\SymfonyHttpBundle\Interfaces\ValidationExceptionInterface;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class RequestPayloadValidationFailedException extends RuntimeException implements ValidationExceptionInterface
{
    private ConstraintViolationListInterface $violationList;
    private string $class;
    private array $payload;

    public function __construct(ConstraintViolationListInterface $violationList, string $class, array $payload)
    {
        parent::__construct('Request payload validation failed');

        $this->violationList = $violationList;
        $this->class = $class;
        $this->payload = $payload;
    }

    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
