<?php

namespace BinSoul\Test\Common\I18n\DateTime;

use BinSoul\Common\I18n\Intl\DateTime\PropertyBag;
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
            [new \DateTime()],
        ];
    }

    /**
     * @param $property
     * @dataProvider properties
     */
    public function test_get_and_set($property): void
    {
        $bag = new PropertyBag();
        $bag->set('prop1', $property);

        $this->assertTrue($bag->has('prop1'));
        $this->assertFalse($bag->has('prop2'));
        $this->assertEquals($property, $bag->get('prop1'));
        $this->assertEquals('missing', $bag->get('prop2', 'missing'));

        $bag->remove('prop1');
        $this->assertFalse($bag->has('prop1'));
        $this->assertEquals('missing', $bag->get('prop1', 'missing'));
    }

    /**
     * @param $property
     * @dataProvider properties
     */
    public function test_array_access($property): void
    {
        $bag = new PropertyBag();
        $bag['prop1'] = $property;
        $this->assertTrue(isset($bag['prop1']));
        $this->assertFalse(isset($bag['prop2']));
        $this->assertEquals($property, $bag['prop1']);

        unset($bag['prop1']);
        $this->assertFalse(isset($bag['prop1']));
    }

    /**
     * @param $property
     * @dataProvider properties
     */
    public function test_returns_iterator($property): void
    {
        $bag = new PropertyBag(['prop1' => $property]);
        foreach ($bag as $key => $value) {
            $this->assertEquals('prop1', $key);
            $this->assertEquals($property, $value);
        }
    }

    public function test_modifies_properties(): void
    {
        $bag = new PropertyBag(['prop1' => 'prop1', 'prop2' => 'prop2']);
        $this->assertEquals(2, $bag->count());
        $this->assertEquals(['prop1', 'prop2'], $bag->keys());
        $this->assertEquals(['prop1' => 'prop1', 'prop2' => 'prop2'], $bag->all());

        $bag->remove('prop2');
        $this->assertEquals(1, $bag->count());
        $this->assertEquals(['prop1'], $bag->keys());
        $this->assertEquals(['prop1' => 'prop1'], $bag->all());

        $bag->replace(['prop2' => 'prop2']);
        $this->assertEquals(1, $bag->count());
        $this->assertEquals(['prop2'], $bag->keys());
        $this->assertEquals(['prop2' => 'prop2'], $bag->all());

        $bag->add(['prop1' => 'prop1']);
        $this->assertEquals(2, $bag->count());
        $this->assertEquals(['prop2', 'prop1'], $bag->keys());
        $this->assertEquals(['prop2' => 'prop2', 'prop1' => 'prop1'], $bag->all());
    }
}
