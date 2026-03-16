<?php

declare(strict_types=1);

namespace BinSoul\Common\I18n\Intl\DateTime;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * PropertyBag is a container for key/value pairs.
 *
 * @implements IteratorAggregate<string, mixed>
 * @implements ArrayAccess<string, mixed>
 */
class PropertyBag implements IteratorAggregate, Countable, ArrayAccess
{
    /**
     * Constructs an instance of this class.
     *
     * @param array<string, mixed> $properties
     */
    public function __construct(
        private array $properties = []
    ) {
    }

    /**
     * Returns the properties.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->properties;
    }

    /**
     * Returns the property keys.
     *
     * @return array<int, string>
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

        $this->properties = $properties;
    }

    /**
     * Returns a property by name.
     *
     * @param string $key     The key
     * @param mixed  $default The default value if the property key does not exist
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $this->properties) ? $this->properties[$key] : $default;
    }

    /**
     * Sets a property by name.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     */
    public function set(string $key, mixed $value): void
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
     * @param string $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return is_string($offset) && $this->has($offset);
    }

    /**
     * @param string $offset
     *
     * @return mixed|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (! is_string($offset)) {
            return null;
        }

        return $this->get($offset);
    }

    /**
     * @param string     $offset
     * @param mixed|null $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_string($offset)) {
            $this->set($offset, $value);
        }
    }

    /**
     * @param string $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        if (is_string($offset)) {
            $this->remove($offset);
        }
    }
}
