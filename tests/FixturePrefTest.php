<?php

namespace ultron\FixturePref;

use JsonException;
use PHPUnit\Framework\TestCase;
use ultron\FixturePref\Generators\RandomBytesGenerator;
use ultron\FixturePref\Key\DefaultPrefKey;

class FixturePrefTest extends TestCase
{
    public function testInitGenerator(): void
    {
        $this->assertFalse(FixturePref::initGenerator());
    }
    public function testAddWithoutGenerator(): void
    {
        $key = new DefaultPrefKey(TestKeyType::TEST_NO_GENERATOR->value);
        $value = FixturePref::addPref($key);
        $this->assertSame(['TEST_NO_GENERATOR' => ['default' => [$value]]],FixturePref::$pref);
    }

    public function testClearPref(): void
    {
        $key = new DefaultPrefKey(TestKeyType::TEST_NO_GENERATOR->value);
        FixturePref::clearPrefGroup(key: $key);
        $this->assertSame([],FixturePref::$pref);
    }

    public function testSetGenerator(): void
    {
        FixturePref::setGenerator(new RandomBytesGenerator());
        $this->assertTrue(FixturePref::initGenerator());
    }

    public function testGetPref(): void
    {
        $this->assertSame([], FixturePref::getPref());
        $key = new DefaultPrefKey(TestKeyType::TEST_GET_PREF->value);

        /** @var array<array-key, string> $results */
        $results = [];
        for ($i = 1; $i<= 10; $i++) {
            $results[] = FixturePref::addPref($key);
        }

        $this->assertSame(["TEST_GET_PREF" => ['default' => $results]], FixturePref::getPref());
    }

    public function testGetAllPref(): void
    {
        $key = new DefaultPrefKey(TestKeyType::TEST_GET_ALL_PREF->value);

        /** @var array<array-key, string> $results */
        $results = [];
        for ($i = 1; $i<= 10; $i++) {
            $results[] = FixturePref::addPref(key: $key);
        }

        $this->assertSame(count($results), 10);
        $this->assertSame($results, FixturePref::getAllPref(key: $key));
    }

    public function testAddPref(): void
    {
        $key = new DefaultPrefKey(TestKeyType::TEST_ADD_PREF->value);

        $result = FixturePref::addPref($key);
        for ($i = 1; $i<= 10; $i++) {
            FixturePref::addPref($key);
        }
        $this->assertContains($result, FixturePref::getAllPref($key));
    }

    public function testGetRandomPref(): void
    {
        $key = new DefaultPrefKey(TestKeyType::TEST_GET_RANDOM_PREF->value);

        $results = [];
        for ($i = 1; $i<= 10; $i++) {
            $results[] = FixturePref::addPref(key: $key);
        }

        $randomPref = FixturePref::getRandomPref(key: $key);

        $this->assertContains($randomPref, $results);
    }

    /**
     * @throws JsonException
     */
    public function testGetRandomOrNullPref(): void
    {
        $key = new DefaultPrefKey(TestKeyType::TEST_GET_RANDOM_PREF_OR_NULL->value);

        $results = [];
        for($i = 1; $i<= 25; $i++){
            $results[] = FixturePref::addPref(key: $key);
        }

        $strResults = json_encode($results, JSON_THROW_ON_ERROR);

        for($i=1; $i<=5; $i++){
            $randomPref = FixturePref::getRandomOrNullPref(key: $key);
            $this->assertTrue(null === $randomPref || in_array($randomPref, $results,true), "$randomPref not in array [$strResults]");
        }
    }

    public function testClearAllPref(): void
    {
        $this->assertNotSame([], FixturePref::$pref);
        FixturePref::clearAllPref();
        $this->assertSame([], FixturePref::$pref);
    }
}
