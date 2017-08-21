<?php

namespace YachtFocus\Library;

use BadMethodCallException;
use InvalidArgumentException;
use JsonSerializable;
use ReflectionClass;

/**
 * This Enum implementation allows the creation, validation, comparison and passing of Enum objects
 *
 * An Enum is used when a known state is to be passed without using magic strings.
 *
 * Example usage:
 * class myEnum extends Enum { const X = "x"; const Y = "y" }
 *
 * As these are virtual methods, you can add some phpdocs for the class:
 *
 * @ method static myEnum A()
 * @ method static myEnum B()
 *
 * (replace spaces after @ of course)
 *
 * $a = myEnum::A();
 * $b= myEnum::B();
 *
 * $a->equalTo($b) // false
 * Enum::equals($a, $b) // false
 * $a instanceof myEnum // true
 */
class Enum implements JsonSerializable
{
    /**
     * @var array A cache of all enum values to increase performance
     */
    protected static $cache = [];

    /**
     * @var string
     */
    protected $key;

    /****
     * Enum constructor.
     *
     * @param string $key
     */
    private function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param string $name      The name of the method being called
     * @param array  $arguments The arguments passed to the method
     *
     * @return static
     * @throws InvalidArgumentException
     */
    public static function __callStatic($name, array $arguments)
    {
        /** @var static $class */
        $class = get_called_class();

        return $class::getByKey($name);
    }

    /**
     * @param string $key
     *
     * @return static
     * @throws InvalidArgumentException
     */
    public static function getByKey($key)
    {
        /** @var static $class */
        $class = get_called_class();

        if (!in_array($key, $class::keys(), true)) {
            throw new InvalidArgumentException('Key ' . $key . ' does not exist');
        }

        return new $class($key);
    }

    /**
     * Returns the names (or keys) of all of constants in the enum
     *
     * @return array
     */
    public static function keys()
    {
        return array_keys(static::values());
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $values = static::values();

        return $values[$this->key];
    }

    /**
     * Return the names and values of all the constants in the enum
     *
     * @return array
     */
    public static function values()
    {
        $class = get_called_class();

        if (!isset(self::$cache[$class])) {
            $reflected           = new ReflectionClass($class);
            self::$cache[$class] = $reflected->getConstants();
        }

        return self::$cache[$class];
    }

    /**
     * @param Enum|string $enum1
     * @param Enum|string $enum2
     *
     * @return bool
     * @throws BadMethodCallException
     */
    public static function equals($enum1, $enum2)
    {
        /** @var static $class */
        $class = get_called_class();

        if (!is_subclass_of($class, self::class)) {
            throw new BadMethodCallException('Don\'t call equals() on Enum, only on childs of Enum');
        }

        if (!$enum1 instanceof static) {
            try {
                $enum1 = $class::getByKey($enum1);
            } catch (InvalidArgumentException $e) {
                return false;
            }
        }

        if (!$enum2 instanceof static) {
            try {
                $enum2 = $class::getByKey($enum2);
            } catch (InvalidArgumentException $e) {
                return false;
            }
        }

        return $enum1->getKey() === $enum2->getKey();
    }

    /**
     * @param Enum|string $enum
     *
     * @return bool
     */
    public function equalTo($enum)
    {
        /** @var static $class */
        $class = get_called_class();

        if (!$enum instanceof static) {
            try {
                $enum = $class::getByKey($enum);
            } catch (InvalidArgumentException $e) {
            }
        }

        return $this->getKey() === $enum->getKey();
    }

    /**
     * @param Enum[]|string[] $enums
     *
     * @return bool
     */
    public function equalToOneOf($enums)
    {
        foreach ($enums as $enum) {
            if ($this->equalTo($enum)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->key;
    }
}
