<?php

declare(strict_types=1);

namespace phuongaz\zoneevent\zone;

use ReflectionException;

final class ZonePool {

    /** @var Zone[] */
    private static array $zones = [];

    public static function addZone(Zone $zone) : void {
        self::$zones[$zone->getName()] = $zone;
    }

    public static function getZone(string $name) : ?Zone {
        return self::$zones[$name] ?? null;
    }

    public static function getZones() : array {
        return self::$zones;
    }

    /**
     * @throws ReflectionException
     */
    public static function removeZone(string $name) : void {
        $zone = self::getZone($name);
        $zone->removeHandlers();
        unset(self::$zones[$name]);
    }

    public static function isZone(string $name) : bool {
        return isset(self::$zones[$name]);
    }
}