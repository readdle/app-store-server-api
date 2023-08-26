<?php
declare(strict_types=1);

namespace Readdle\AppStoreServerAPI\Request;

use Error;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use TypeError;
use UnexpectedValueException;

use function array_combine;
use function array_filter;
use function array_key_exists;
use function array_map;
use function explode;
use function get_class;
use function gettype;
use function in_array;
use function is_scalar;
use function join;
use function lcfirst;
use function strpos;
use function strtolower;
use function ucfirst;
use function var_export;
use function vsprintf;

use const ARRAY_FILTER_USE_KEY;

abstract class AbstractRequestParamsBag
{
    /**
     * @var array<string>
     */
    protected array $requiredFields = [];

    /**
     * @param array<string, mixed> $params
     */
    public function __construct(array $params = [])
    {
        $reflection = new ReflectionClass($this);

        $protectedProps = array_filter(
            $reflection->getProperties(),
            fn ($property) => $property->isProtected() && $property->getName() !== 'requiredFields'
        );
        $protectedProps = array_combine(array_map(fn ($p) => $p->getName(), $protectedProps), $protectedProps);

        $requiredFields = $this->requiredFields;

        if (in_array('*', $requiredFields)) {
            $requiredFields = array_keys($protectedProps);
        }

        $diff = array_diff($requiredFields, array_keys($params));

        if ($diff) {
            throw new Error(vsprintf(
                '[%s] Required fields are missing: ["' . join('","', $diff) . '"]',
                [get_class($this)]
            ));
        }

        $propConsts  = array_filter(
            $reflection->getConstants(),
            fn ($const) => strpos($const, '__') !== false,
            ARRAY_FILTER_USE_KEY
        );

        /** @var array<string, mixed> $propValues */
        $propValues = [];

        foreach ($propConsts as $constName => $constValue) {
            $camelPropName =  lcfirst(join(array_map(
                fn ($part) => ucfirst(strtolower($part)),
                explode('_', explode('__', $constName)[0])
            )));
            $propValues[$camelPropName][] = $constValue;
        }

        foreach ($params as $name => $value) {
            if ($name === 'revision') {
                throw new Error(vsprintf('[%s] Revision could not be set as a parameter', [get_class($this)]));
            }

            if (!array_key_exists($name, $protectedProps)) {
                throw new Error(vsprintf('[%s] Unrecognized parameter "%s"', [get_class($this), $name]));
            }

            if (!$this->isValueMatchingPropType($value, $protectedProps[$name])) {
                throw new TypeError(vsprintf(
                    '[%s] Parameter "%s" is of wrong type "%s" ("%s" is expected)',
                    [get_class($this), $name, gettype($value), $protectedProps[$name]->getType()]
                ));
            }

            if (isset($propValues[$name]) && !$this->isValueMatchingPropValues($value, $propValues[$name])) {
                throw new UnexpectedValueException(vsprintf(
                    '[%s] Parameter "%s" has wrong value %s',
                    [get_class($this), $name, var_export($value, true)]
                ));
            }

            $this->$name = $value;
        }
    }

    /**
     * @param mixed $value
     */
    protected function isValueMatchingPropType($value, ReflectionProperty $prop): bool
    {
        /** @var ReflectionNamedType $propType */
        $propType = $prop->getType();
        $propTypeName = $propType->getName();

        switch ($propTypeName) {
            case 'int':
                $propTypeName = 'integer';
                break;

            case 'bool':
                $propTypeName = 'boolean';
                break;
        }

        if ($propTypeName === 'array') {
            // we don't know what type of value it should be
            // probably, the following check for value will do the work
            return true;
        }

        if (!is_scalar($value)) {
            // if the prop's type is not 'array' the value type should match it and, thus, it can't be not scalar
            return false;
        }

        return $propTypeName === gettype($value);
    }

    /**
     * @param mixed $value
     * @param array<string, mixed> $propValues
     */
    protected function isValueMatchingPropValues($value, array $propValues): bool
    {
        if (is_scalar($value)) {
            return in_array($value, $propValues);
        }

        if (is_array($value)) {
            return !!array_filter($value, fn ($v) => !in_array($v, $propValues));
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    protected function collectProps(): array
    {
        $props = [];

        $reflection = new ReflectionClass($this);
        $protectedProps = array_filter($reflection->getProperties(), fn ($property) => $property->isProtected());

        foreach ($protectedProps as $prop) {
            $propName = $prop->getName();
            $value = $this->$propName;
            $defaultValue = $prop->getDeclaringClass()->getDefaultProperties()[$propName] ?? null;

            if (!isset($value) || $value === $defaultValue) {
                continue;
            }

            if (is_array($value)) {
                $props[$propName] = array_merge($props[$propName] ?? [], $value);
            } else {
                $props[$propName] = $value;
            }
        }

        return $props;

    }
}
