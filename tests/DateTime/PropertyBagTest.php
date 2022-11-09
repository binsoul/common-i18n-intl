<?php

declare(strict_types=1);

namespace BinSoul\Test\Common\I18n\Intl\DateTime;

use BinSoul\Common\I18n\Intl\DateTime\PropertyBag;
use DateTime;
use PHPUnit\Framework\TestCase;

class PropertyBagTest extends TestCase
{
    public function properties(): array
    {
        return [
            [null],
            [1],
            [0],
            [true],
            [false],
            ['test'],
            [new DateTime()],
        ];
    }

    /**
     * @dataProvider properties
     */
    public function test_get_and_set($property): void
    {
        $bag = new PropertyBag();
        $bag->set('prop1', $property);

        self::assertTrue($bag->has('prop1'));
        self::assertFalse($bag->has('prop2'));
        self::assertEquals($property, $bag->get('prop1'));
        self::assertEquals('missing', $bag->get('prop2', 'missing'));

        $bag->remove('prop1');
        self::assertFalse($bag->has('prop1'));
        self::assertEquals('missing', $bag->get('prop1', 'missing'));
    }

    /**
     * @dataProvider properties
     */
    public function test_array_access($property): void
    {
        $bag = new PropertyBag();
        $bag['prop1'] = $property;
        self::assertTrue(isset($bag['prop1']));
        self::assertFalse(isset($bag['prop2']));
        self::assertEquals($property, $bag['prop1']);

        unset($bag['prop1']);
        self::assertFalse(isset($bag['prop1']));
    }

    /**
     * @dataProvider properties
     */
    public function test_returns_iterator($property): void
    {
        $bag = new PropertyBag(['prop1' => $property]);

        foreach ($bag as $key => $value) {
            self::assertEquals('prop1', $key);
            self::assertEquals($property, $value);
        }
    }

    public function test_modifies_properties(): void
    {
        $bag = new PropertyBag(['prop1' => 'prop1', 'prop2' => 'prop2']);
        self::assertEquals(2, $bag->count());
        self::assertEquals(['prop1', 'prop2'], $bag->keys());
        self::assertEquals(['prop1' => 'prop1', 'prop2' => 'prop2'], $bag->all());

        $bag->remove('prop2');
        self::assertEquals(1, $bag->count());
        self::assertEquals(['prop1'], $bag->keys());
        self::assertEquals(['prop1' => 'prop1'], $bag->all());

        $bag->replace(['prop2' => 'prop2']);
        self::assertEquals(1, $bag->count());
        self::assertEquals(['prop2'], $bag->keys());
        self::assertEquals(['prop2' => 'prop2'], $bag->all());

        $bag->add(['prop1' => 'prop1']);
        self::assertEquals(2, $bag->count());
        self::assertEquals(['prop2', 'prop1'], $bag->keys());
        self::assertEquals(['prop2' => 'prop2', 'prop1' => 'prop1'], $bag->all());
    }
}
