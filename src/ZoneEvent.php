<?php

declare(strict_types=1);

namespace phuongaz\zoneevent;

use phuongaz\zoneevent\zone\Zone;
use phuongaz\zoneevent\zone\ZonePool;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use ReflectionException;

class ZoneEvent extends PluginBase {
    use SingletonTrait;

    public function onEnable() : void {
        self::setInstance($this);
    }

    public static function register(Zone $zone) : void {
        ZonePool::addZone($zone);
    }

    /**
     * @throws ReflectionException
     */
    public static function unregister(string $name) : void {
        ZonePool::removeZone($name);
    }

    public static function getZone(string $name) : ?Zone {
        return ZonePool::getZone($name);
    }

    public static function getZones() : array {
        return ZonePool::getZones();
    }

}