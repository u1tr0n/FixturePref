<?php
declare(strict_types=1);

namespace ultron\FixturePref;
use ultron\FixturePref\Generators\IdGeneratorInterface;
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
        if (!array_key_exists(key: $key->value, array: self::$pref)) {
            self::$pref[$key->value] = [];
        }

        if (!array_key_exists($subKey, self::$pref[$key->value])) {
            self::$pref[$key->value][$subKey] = [];
        }

        $id = self::$generator->createId();

        self::$pref[$key->value][$subKey][] = $id;

        return $id;
    }

    /**
     * @return array<array-key, string>
     */
    public static function getAllPref(UnitEnum $key, string $subKey = 'default'): array
    {
        if (array_key_exists($key->value, self::$pref)) {
            return self::$pref[$key->value][$subKey];
        }

        return [];
    }

    public static function getRandomPref(UnitEnum $key, string $subKey = 'default'): ?string
    {
        if (array_key_exists($key->value, self::$pref)) {
            $count = count(self::$pref[$key->value][$subKey]);

            try {
                return self::$pref[$key->value][$subKey][random_int(0, $count - 1)];
            } catch (\Exception) {
                return null;
            }
        }

        return null;
    }

    public static function getRandomOrNullPref(UnitEnum $key, int $percent = 50, string $subKey = 'default'): ?string
    {
        try {
            if (random_int(0, 100) > $percent) {
                return self::getRandomPref($key, $subKey);
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
}
