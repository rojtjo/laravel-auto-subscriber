<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;

final class FindListeners
{
    /**
     * @return Collection<string, Collection<int, class-string>>
     */
    public static function for(object $subscriber): Collection
    {
        $reflectionClass = new ReflectionClass($subscriber);

        return collect($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter(self::hasOneClassParameter(...))
            ->keyBy(self::handlerMethod(...))
            ->except('subscribe')
            ->map(self::eventName(...));
    }

    private static function hasOneClassParameter(ReflectionMethod $method): bool
    {
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
    }

    private static function handlerMethod(ReflectionMethod $method): string
    {
        return $method->getName();
    }

    /**
     * @return Collection<int, class-string>
     */
    private static function eventName(ReflectionMethod $method): Collection
    {
        [$parameter] = $method->getParameters();

        /** @var ReflectionNamedType|ReflectionUnionType $type */
        $type = $parameter->getType();
        $types = $type instanceof ReflectionUnionType
            ? $type->getTypes()
            : [$type];

        return collect($types)
            ->map(fn (ReflectionNamedType $type) => $type->getName());
    }
}
