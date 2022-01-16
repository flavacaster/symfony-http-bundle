<?php

declare(strict_types=1);

namespace Flavacaster\SymfonyHttpBundle\Service;

use Doctrine\Common\Annotations\Reader;
use Flavacaster\SymfonyHttpBundle\Annotations\ValueMutatorInterface;
use N7\SymfonyValidatorsBundle\Validator\NestedObject;
use N7\SymfonyValidatorsBundle\Validator\NestedObjects;
use ReflectionClass;
use ReflectionProperty;

final class AnnotationsHandler
{
    private Reader $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function apply(string $class, array $payload): array
    {
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getProperties() as $propery) {
            if (! array_key_exists($propery->getName(), $payload)) {
                continue;
            }

            // Applying mutators
            $mutators = $this->getPropertyMutators($propery);
            foreach ($mutators as $mutator) {
                $payload[$propery->getName()] = $mutator->mutate(
                    $payload[$propery->getName()],
                    $payload,
                    $propery->getName()
                );
            }

            // If nested object
            if (
                ($nestedObject = $this->getPropertyNestedObjectClass($propery))
                && is_array($payload[$propery->getName()])
            ) {
                $payload[$propery->getName()] = $this->apply($nestedObject, $payload[$propery->getName()]);
            }

            // If array of objects
            if (
                ($nestedObjects = $this->getPropertyNestedObjectsClass($propery))
                && is_array($payload[$propery->getName()])
            ) {
                $payload[$propery->getName()] = array_map(
                    fn ($item) => is_array($item) ? $this->apply($nestedObjects, $item) : $item,
                    $payload[$propery->getName()]
                );
            }
        }

        return $payload;
    }

    private function getPropertyMutators(ReflectionProperty $property): array
    {
        $annotations = $this->annotationReader->getPropertyAnnotations($property);

        $annotations = array_filter(
            $annotations,
            fn ($annotation): bool => $annotation instanceof ValueMutatorInterface
        );

        return array_values($annotations);
    }

    private function getPropertyNestedObjectClass(ReflectionProperty $property): ?string
    {
        $annotations = $this->annotationReader->getPropertyAnnotations($property);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof NestedObject) {
                return $annotation->class;
            }
        }

        return null;
    }

    private function getPropertyNestedObjectsClass(ReflectionProperty $property): ?string
    {
        $annotations = $this->annotationReader->getPropertyAnnotations($property);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof NestedObjects) {
                return $annotation->class;
            }
        }

        return null;
    }
}
