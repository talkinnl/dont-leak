<?php

declare(strict_types=1);

namespace Talkinnl\DontLeak;

use PHPUnit\Framework\Test;

class DontLeak
{
    private const NAMESPACE_PREFIX_LARAVEL = 'Illuminate';
    private const NAMESPACE_PREFIX_PHPUNIT = 'PHPUnit';

    /**
     * Unsets all properties reachable in own scope of given $object, and all private properties of parent objects.
     * Properties defined by PHPUnit WON'T be touched.
     */
    public static function freeOwnProperties(Test $test): void
    {
        $unsetter = function (string $propertyName): void {
            unset($this->$propertyName);
        };

        foreach (self::getAllProperties($test) as $property) {
            if (self::isSafeToFreeProperty($property)) {
                $unsetter = $unsetter->bindTo($test, $property->getDeclaringClass()->getName());
                $unsetter($property->getName());
            }
        }
    }

    private static function getAllProperties(Test $test): \Generator
    {
        $object = new \ReflectionObject($test);

        // Start with all properties visible to this test instance
        yield from $object->getProperties();
        while ($object->getParentClass()) {
            $object = $object->getParentClass();

            if (self::dontTouch($object->getNamespaceName())) {
                // Done, these parent classes should do their own cleanup.
                break;
            }

            // Check parent classes for private properties
            yield from $object->getProperties(\ReflectionProperty::IS_PRIVATE);
        }
    }

    private static function isSafeToFreeProperty(\ReflectionProperty $property): bool
    {
        if ($property->isStatic()) {
            return false;
        }

        // Don't touch visible properties which are declared by the frameworks
        return !self::dontTouch($property->getDeclaringClass()->getNamespaceName());
    }

    private static function dontTouch(string $namespace): bool {
        return
            \str_starts_with($namespace, self::NAMESPACE_PREFIX_PHPUNIT) ||
            \str_starts_with($namespace, self::NAMESPACE_PREFIX_LARAVEL);
    }
}
