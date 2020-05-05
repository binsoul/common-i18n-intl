<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * PropertyBag is a container for key/value pairs.
 */
class PropertyBag implements IteratorAggregate, Countable, ArrayAccess
{
    /**
     * @var array<string, mixed>
     */
    private $properties;

    /**
     * Constructs an instance of this class.
     *
     * @param mixed[] $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    /**
     * Returns the properties.
     *
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->properties;
    }

    /**
     * Returns the property keys.
     *
     * @return string[]
     */
    public function keys(): array
    {
        return array_keys($this->properties);
    }

    /**
     * Replaces the current properties by a new set.
     *
     * @param array<string, mixed> $properties
     */
    public function replace(array $properties = []): void
    {
        $this->properties = $properties;
    }

    /**
     * Adds properties.
     *
     * @param array<string, mixed> $properties
     */
    public function add(array $properties = []): void
    {
        $properties = array_replace($this->properties, $properties);

        if ($properties !== null) {
            $this->properties = $properties;
        }
    }

    /**
     * Returns a property by name.
     *
     * @param string $key     The key
     * @param mixed  $default The default value if the property key does not exist
     *
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return array_key_exists($key, $this->properties) ? $this->properties[$key] : $default;
    }

    /**
     * Sets a property by name.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     */
    public function set(string $key, $value): void
    {
        $this->properties[$key] = $value;
    }

    /**
     * Returns true if the property is defined.
     *
     * @param string $key The key
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->properties);
    }

    /**
     * Removes a property.
     */
    public function remove(string $key): void
    {
        unset($this->properties[$key]);
    }

    /**
     * Returns an iterator for properties.
     *
     * @return ArrayIterator<string, mixed>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->properties);
    }

    /**
     * Returns the number of properties.
     */
    public function count(): int
    {
        return count($this->properties);
    }

    /**
     * @param string|mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @param string|mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string|mixed $offset
     * @param mixed|null   $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @param string|mixed $offset
     */
    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }
}
