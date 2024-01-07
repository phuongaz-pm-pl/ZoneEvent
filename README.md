# ZoneEvent

Simple api for pocketmine creates an event area.

### Example

Dungeon event zone
```php

$pos1 = new Vector3(0, 0, 0);
$pos2 = new Vector3(10, 10, 10);
$spawn = new Vector3(5, 5, 5);
$worldName = "world";

$zone = new Zone("Example Dungeon", $pos1, $pos2, $spawn, $worldName);

$zone->addHandler(new ZoneHandler(PlayerMoveEvent::class, function(PlayerMoveEvent $event, Zone $zone){
    $player = $event->getPlayer();
    $player->sendPopup("You are in the zone: " . $zone->getName());
}));

$entityDeathHandler = new ZoneHandler(EntityDeathEvent::class, function(EntityDeathEvent $event, Zone $zone){
    $entity = $event->getEntity();
    if($entity instanceof DungeonMob){
        Dungeon::addDeath($entity);
    }
    ...
});
$zone->addHandler($entityDeathHandler);

ZoneEvent::register($zone);
```
Event zone with start and end time
```php
$start = new DateTime("now");
$end = clone $start;
$end->add(new DateInterval('PT1H')); // Add 1 hour to $end

$zone->setStartTime($start);
$zone->setEndTime($end);

$zone->addHandler(new ZoneHandler(BlockPlaceEvent::class, function(BlockPlaceEvent $event, Zone $zone){
    if(!$zone->inEvent()){
        // is not in event
        $event->setCancelled();
        return;
    }
}));
```