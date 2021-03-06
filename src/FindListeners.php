<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use Illuminate\Support\Collection;
use ReflectionMethod;
use ReflectionNamedType;

final class FindListeners
{
    public static function for(object $subscriber): Collection
    {
        $reflectionClass = new \ReflectionClass($subscriber);

        return collect($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter(self::hasOneClassParameter())
            ->keyBy(self::handlerMethod())
            ->except('subscribe')
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
            if ($type->isBuiltin()) {
                return false;
            }

            if (! $type instanceof ReflectionNamedType) {
                return false;
            }

            return true;
        };
    }

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

            /** @var ReflectionNamedType $type */
            $type = $parameter->getType();

            return $type->getName();
        };
    }
}
