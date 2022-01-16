<?php

declare(strict_types=1);

namespace Flavacaster\SymfonyHttpBundle\ArgumentResolver;

use Flavacaster\SymfonyHttpBundle\Interfaces\RequestPayloadInterface;
use Flavacaster\SymfonyHttpBundle\Service\RequestResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Generator;

final class RequestPayloadValueResolver implements ArgumentValueResolverInterface
{
    private RequestResolver $requestResolver;

    public function __construct(RequestResolver $requestResolver)
    {
        $this->requestResolver = $requestResolver;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();
        if (! $type || ! class_exists($type)) {
            return false;
        }

        $interfaces = class_implements($type);

        return array_key_exists(RequestPayloadInterface::class, $interfaces);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        yield $this->requestResolver->resolve($request, $argument->getType());
    }
}
