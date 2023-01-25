<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Request;

use Error;
use ReflectionClass;
use ReflectionProperty;
use TypeError;
use ValueError;
use function array_filter;
use function array_map;
use function explode;
use function get_class;
use function gettype;
use function in_array;
use function is_scalar;
use function join;
use function str_contains;
use function strtolower;
use function ucfirst;
use function var_export;
use function vsprintf;

abstract class AbstractQueryParams
{
    public function __construct(array $params = [])
    {
        $reflection = new ReflectionClass($this);

        $protectedProps = array_filter($reflection->getProperties(), fn ($property) => $property->isProtected());
        $protectedProps = array_combine(array_map(fn($p) => $p->getName(), $protectedProps), $protectedProps);
        $propConsts  = array_filter($reflection->getConstants(), fn ($const) => str_contains('__', $const));

        $propValues = [];

        foreach ($propConsts as $constName => $constValue) {
            [$snakePropName] = explode('__', $constName);
            $camelPropName =  join(array_map(fn ($part) => ucfirst(strtolower($part)), explode('_', $snakePropName)));
            $propValues[$camelPropName][] = $constValue;
        }

        foreach ($params as $name => $value) {
            if (!array_key_exists($name, $protectedProps)) {
                throw new Error(vsprintf(
                    '[%s] Unrecognized query parameter "%s"',
                    [get_class($this), $name]
                ));
            }

            if (!$this->isValueMatchingPropType($value, $protectedProps[$name])) {
                throw new TypeError(vsprintf(
                    '[%s] Query parameter "%s" is of wrong type "%s" ("%s" is expected)',
                    [get_class($this), $name, gettype($value), $protectedProps[$name]->getType()]
                ));
            }

            if (isset($propValues[$name]) && !$this->isValueMatchingPropValues($value, $propValues[$name])) {
                throw new ValueError(vsprintf(
                    '[%s] Query parameter "%s" has wrong value %s',
                    [get_class($this), $name, var_export($value, true)]
                ));
            }

            $this->$name = $value;
        }
    }

    protected function isValueMatchingPropType(mixed $value, ReflectionProperty $prop): bool
    {
        $propType = (string) $prop->getType();

        if ($propType === 'array') {
            // we don't know what type of value it should be
            // probably, the following check for value (if const(s) exist(s)) will do the work
            return true;
        }

        if (!is_scalar($value)) {
            // if the prop's type is not 'array' the value type should match it and, thus, it can't be not scalar
            return false;
        }

        return $propType === gettype($value);
    }

    protected function isValueMatchingPropValues(mixed $value, array $propValues): bool
    {
        if (is_scalar($value)){
            return in_array($value, $propValues);
        }

        if (is_array($value)) {
            return !!array_filter($value, fn ($v) => !in_array($v, $propValues));
        }

        return false;
    }

    public function getQueryString(): string
    {
        $reflection = new ReflectionClass($this);
        $protectedProps = array_filter($reflection->getProperties(), fn ($property) => $property->isProtected());
        $queryStringParams = [];

        foreach ($protectedProps as $prop) {
            $propName = $prop->getName();
            $value = $this->$propName;

            if (empty($value) || $value === $prop->getDefaultValue()) {
                continue;
            }

            $queryStringParams[$propName] = $value;
        }

        return http_build_query($queryStringParams);
    }
}
