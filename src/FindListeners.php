<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use Illuminate\Support\Collection;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;

final class FindListeners
{
    /**
     * @return Collection<string, array<string>>
     */
    public static function for(object $subscriber): Collection
    {
        $reflectionClass = new \ReflectionClass($subscriber);

        return collect($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter(self::hasOneClassParameter())
            ->keyBy(self::handlerMethod())
            ->except(['subscribe'])
            ->map(self::eventName());
    }

    private static function hasOneClassParameter(): callable
    {
        return function (ReflectionMethod $method) {
            if ($method->getNumberOfParameters() !== 1) {
                return false;
            }

            [$parameter] = $method->getParameters();
            if (! $parameter->hasType()) {
                return false;
            }

            $type = $parameter->getType();
            if ($type instanceof ReflectionIntersectionType) {
                return false;
            }

            $types = $type instanceof ReflectionUnionType
                ? $type->getTypes()
                : [$type];

            foreach ($types as $type) {
                if (! $type instanceof ReflectionNamedType) {
                    return false;
                }

                if ($type->isBuiltin()) {
                    return false;
                }
            }

            return true;
        };
    }

    /**
     * @return callable(ReflectionMethod): string
     */
    private static function handlerMethod(): callable
    {
        return function (ReflectionMethod $method) {
            return $method->getName();
        };
    }

    private static function eventName(): callable
    {
        return function (ReflectionMethod $method) {
            [$parameter] = $method->getParameters();

            /** @var ReflectionNamedType|ReflectionUnionType $type */
            $type = $parameter->getType();
            $types = $type instanceof ReflectionUnionType
                ? $type->getTypes()
                : [$type];

            return array_map(
                fn (ReflectionNamedType $type) => $type->getName(),
                $types,
            );
        };
    }
}
