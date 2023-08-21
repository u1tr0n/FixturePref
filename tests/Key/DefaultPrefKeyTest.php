<?php

namespace ultron\FixturePref\Key;

use PHPUnit\Framework\TestCase;

class DefaultPrefKeyTest extends TestCase
{
    private string $group = 'test_group';
    private string $name = 'test_name';

    public function test__construct(): void
    {
        $key = new DefaultPrefPrefKey(group: $this->group, name: $this->name);
        $this->assertSame(expected: $this->group, actual: $key->getGroup());
        $this->assertSame(expected: $this->name, actual: $key->getKey());
    }

    public function testGetGroup(): void
    {
        $key = new DefaultPrefPrefKey(group: $this->group, name: $this->name);
        $this->assertSame(expected: $this->group, actual: $key->getGroup());
    }

    public function testGetKey(): void
    {
        $key = new DefaultPrefPrefKey(group: $this->group, name: $this->name);
        $this->assertSame(expected: $this->name, actual: $key->getKey());
    }
    public function testGetDefaultKey(): void
    {
        $key = new DefaultPrefPrefKey(group: $this->group);
        $this->assertSame(expected: 'default', actual: $key->getKey());
    }
}
