<?php
declare(strict_types=1);

namespace ultron\FixturePref;
use ultron\FixturePref\Generators\IdGeneratorInterface;
use ultron\FixturePref\Generators\RandomBytesGenerator;
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

    public static function addPref(UnitEnum $key, string $subKey = 'default'): string
    {
        if (false === self::$hasGenerator) {
            self::setGenerator(new RandomBytesGenerator());
        }

        $arrayKey = $key->name;

        if (!array_key_exists(key: $arrayKey, array: self::$pref)) {
            self::$pref[$arrayKey] = [];
        }

        if (!array_key_exists($subKey, self::$pref[$arrayKey])) {
            self::$pref[$arrayKey][$subKey] = [];
        }

        $id = self::$generator->createId();

        self::$pref[$arrayKey][$subKey][] = $id;

        return $id;
    }

    /**
     * @return array<array-key, string>
     */
    public static function getAllPref(UnitEnum $key, string $subKey = 'default'): array
    {
        $arrayKey = $key->name;

        if (array_key_exists($arrayKey, self::$pref)) {
            return self::$pref[$arrayKey][$subKey];
        }

        return [];
    }

    public static function getRandomPref(UnitEnum $key, string $subKey = 'default'): ?string
    {
        $arrayKey = $key->name;

        if (array_key_exists($arrayKey, self::$pref)) {
            $count = count(self::$pref[$arrayKey][$subKey]);

            try {
                return self::$pref[$arrayKey][$subKey][random_int(0, $count - 1)];
            } catch (\Exception) {
                return null;
            }
        }

        return null;
    }

    public static function getRandomOrNullPref(
        UnitEnum $key,
        int $percent = 50,
        string $subKey = 'default'
    ): ?string {
        try {
            if (random_int(0, 100) > $percent) {
                return self::getRandomPref(key: $key, subKey: $subKey);
            }
        } catch (\Exception) {
            return null;
        }

        return null;
    }

    public static function getPref(): array
    {
        return self::$pref;
    }

    public static function clearPref(UnitEnum $key): void
    {
        $arrayKey = $key->name;

        if (array_key_exists(key: $arrayKey, array: self::$pref)) {
            unset(self::$pref[$arrayKey]);
        }
    }
    public static function clearAllPref(): void
    {
        self::$pref = [];
    }
}
