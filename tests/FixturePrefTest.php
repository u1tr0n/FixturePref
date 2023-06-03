<?php

namespace ultron\FixturePref;

use PHPUnit\Framework\TestCase;
use ultron\FixturePref\Generators\RandomBytesGenerator;

class FixturePrefTest extends TestCase
{
    public function testInitGenerator(): void
    {
        $this->assertFalse(FixturePref::initGenerator());
    }

    public function testSetGenerator(): void
    {
        FixturePref::setGenerator(new RandomBytesGenerator());
        $this->assertTrue(FixturePref::initGenerator());
    }

    public function testGetPref(): void
    {
        $this->assertSame([], FixturePref::getPref());

        /** @var array<array-key, string> $results */
        $results = [];
        for ($i = 1; $i<= 10; $i++) {
            $results[] = FixturePref::addPref(TestKeyType::TEST_GET_PREF);
        }

        $this->assertSame(["TEST_GET_PREF" => ['default' => $results]], FixturePref::getPref());
    }

    public function testGetAllPref(): void
    {
        /** @var array<array-key, string> $results */
        $results = [];
        for ($i = 1; $i<= 10; $i++) {
            $results[] = FixturePref::addPref(TestKeyType::TEST_GET_ALL_PREF);
        }

        $this->assertSame(count($results), 10);
        $this->assertSame($results, FixturePref::getAllPref(TestKeyType::TEST_GET_ALL_PREF));
    }

    public function testAddPref(): void
    {
        $result = FixturePref::addPref(TestKeyType::TEST_ADD_PREF);
        for ($i = 1; $i<= 10; $i++) {
            FixturePref::addPref(TestKeyType::TEST_ADD_PREF);
        }
        $this->assertContains($result, FixturePref::getAllPref(TestKeyType::TEST_ADD_PREF));
    }

    public function testGetRandomPref(): void
    {
        $results = [];
        for ($i = 1; $i<= 10; $i++) {
            $results[] = FixturePref::addPref(TestKeyType::TEST_GET_RANDOM_PREF);
        }

        $randomPref = FixturePref::getRandomPref(TestKeyType::TEST_GET_RANDOM_PREF);

        $this->assertContains($randomPref, $results);
    }

    /**
     * @throws \JsonException
     */
    public function testGetRandomOrNullPref(): void
    {
        $results = [];
        for($i = 1; $i<= 25; $i++){
            $results[] = FixturePref::addPref(TestKeyType::TEST_GET_RANDOM_PREF_OR_NULL);
        }

        $strResults = json_encode($results, JSON_THROW_ON_ERROR);

        for($i=1; $i<=5; $i++){
            $randomPref = FixturePref::getRandomOrNullPref(TestKeyType::TEST_GET_RANDOM_PREF_OR_NULL);
            $this->assertTrue(null === $randomPref || in_array($randomPref, $results,true), "{$randomPref} not in array [{$strResults}]");
        }
    }


}
