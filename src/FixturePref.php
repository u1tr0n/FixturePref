<?php
declare(strict_types=1);

namespace ultron\FixturePref;
use Exception;
use ultron\FixturePref\Generators\IdGeneratorInterface;
use ultron\FixturePref\Generators\RandomBytesGenerator;
use ultron\FixturePref\Key\PrefKeyInterface;
use UnitEnum;

final class FixturePref
{
    /** @var array<array-key,array<array-key, array<array-key,string>>> $pref */
    public static array $pref = [];

    private static IdGeneratorInterface $generator;
    private static bool $hasGenerator = false;

    public static function setGenerator(IdGeneratorInterface $generator): void
    {
        self::$generator = $generator;
        self::$hasGenerator = true;
    }
    public static function initGenerator(): bool
    {
        return self::$hasGenerator;
    }

    public static function addPref(PrefKeyInterface $key): string
    {
        if (false === self::$hasGenerator) {
            self::setGenerator(new RandomBytesGenerator());
        }

        if (!array_key_exists(key: $key->getGroup(), array: self::$pref)) {
            self::$pref[$key->getGroup()] = [];
        }

        if (!array_key_exists($key->getKey(), self::$pref[$key->getGroup()])) {
            self::$pref[$key->getGroup()][$key->getKey()] = [];
        }

        $id = self::$generator->createId();

        self::$pref[$key->getGroup()][$key->getKey()][] = $id;

        return $id;
    }

    /**
     * @return array<array-key, string>
     */
    public static function getAllPref(PrefKeyInterface $key): array
    {

        if (
            array_key_exists($key->getGroup(), self::$pref)
            && is_array(self::$pref[$key->getGroup()])
            && array_key_exists($key->getKey(), self::$pref[$key->getGroup()])
        ) {
            return self::$pref[$key->getGroup()][$key->getKey()];
        }

        return [];
    }

    public static function getRandomPref(PrefKeyInterface $key): ?string
    {
        if (array_key_exists($key->getGroup(), self::$pref)) {
            $count = count(self::$pref[$key->getGroup()][$key->getKey()]);

            try {
                return self::$pref[$key->getGroup()][$key->getKey()][random_int(0, $count - 1)];
            } catch (Exception) {
                return null;
            }
        }

        return null;
    }

    public static function getRandomOrNullPref(
        PrefKeyInterface $key,
        int              $percent = 50,
    ): ?string {
        try {
            if (random_int(0, 100) > $percent) {
                return self::getRandomPref(key: $key);
            }
        } catch (Exception) {
            return null;
        }

        return null;
    }

    public static function getPref(): array
    {
        return self::$pref;
    }

    public static function clearPref(PrefKeyInterface $key): void
    {
        if (
            array_key_exists(key: $key->getGroup(), array: self::$pref)
            && array_key_exists(key: $key->getKey(), array: self::$pref[$key->getGroup()])
        ) {
            unset(self::$pref[$key->getGroup()][$key->getKey()]);
        }
    }

    public static function clearPrefGroup(PrefKeyInterface $key): void
    {
        if (array_key_exists(key: $key->getGroup(), array: self::$pref)) {
            unset(self::$pref[$key->getGroup()]);
        }
    }
    public static function clearAllPref(): void
    {
        self::$pref = [];
    }
}
