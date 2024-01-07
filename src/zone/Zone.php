<?php

declare(strict_types=1);

namespace phuongaz\zoneevent\zone;

use DateTime;
use phuongaz\zoneevent\ZoneEvent;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\entity\EntityEvent;
use pocketmine\event\HandlerListManager;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\RegisteredListener;
use pocketmine\math\Vector3;
use pocketmine\Server;
use ReflectionException;

class Zone {

    private Vector3 $pos1;
    private Vector3 $pos2;
    private vector3 $spawn;
    private string $world;

    private string $name;
    private string $description = "";

    private ?DateTime $start = null;
    private ?DateTime $end = null;

    /** @var RegisteredListener[] $handlers */
    private array $handlers = [];

    public function __construct(string $name, Vector3 $pos1, Vector3 $pos2, Vector3 $spawn, string $world) {
        $this->name = $name;
        $this->pos1 = $pos1;
        $this->pos2 = $pos2;
        $this->spawn = $spawn;
        $this->world = $world;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPos1() : Vector3 {
        return $this->pos1;
    }

    public function getPos2() : Vector3 {
        return $this->pos2;
    }

    public function getSpawn() : Vector3 {
        return $this->spawn;
    }

    public function getWorld() : string {
        return $this->world;
    }

    public function getStart() : ?DateTime {
        return $this->start;
    }

    public function getEnd() : ?DateTime {
        return $this->end;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function setDescription(string $description) : void {
        $this->description = $description;
    }

    public function setStart(DateTime $start) : void {
        $this->start = $start;
    }

    public function setEnd(DateTime $end) : void {
        $this->end = $end;
    }

    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function setPos1(Vector3 $pos1) : void {
        $this->pos1 = $pos1;
    }

    public function setPos2(Vector3 $pos2) : void {
        $this->pos2 = $pos2;
    }

    public function compareName(string $name) : bool {
        return $this->world === $name;
    }

    public function inEvent() : bool {
        if ($this->start === null || $this->end === null) {
            return true;
        }
        $now = new DateTime();
        return $now >= $this->start && $now <= $this->end;
    }

    /**
     * @throws ReflectionException
     */
    public function addHandler(ZoneHandler $handler) : void {
        $callback = function($event) use ($handler) {
            if ($this->shouldHandleEvent($event)) {
                $handler->getClosure()($event, $this);
            }
        };

        $this->handlers[$handler->getEventClass()] = Server::getInstance()->getPluginManager()->registerEvent(
            $handler->getEventClass(),
            $callback,
            $handler->getPriority(),
            ZoneEvent::getInstance(),
            $handler->isIgnoreCancelled()
        );
    }

    /**
     * @throws ReflectionException
     */
    public function removeHandlers() : void {
        foreach ($this->handlers as $eventClass => $handler) {
            HandlerListManager::global()->getListFor($eventClass)->unregister($handler);
        }
    }

    private function shouldHandleEvent($event): bool {
        if ($event instanceof PlayerEvent) {
            $entity = $event->getPlayer();
        } elseif ($event instanceof EntityEvent) {
            $entity = $event->getEntity();
        } elseif ($event instanceof BlockEvent) {
            $entity = $event->getBlock();
        } else {
            return false;
        }

        if (!$this->compareName($entity->getWorld()->getFolderName())) {
            return false;
        }

        return $this->inZone($entity->getPosition());
    }

    private function inZone(Vector3 $pos): bool {
        $min = $this->pos1->minComponents($this->pos2);
        $max = $this->pos1->maxComponents($this->pos2);

        return $pos->getX() >= $min->getX() && $pos->getX() <= $max->getX() &&
            $pos->getY() >= $min->getY() && $pos->getY() <= $max->getY() &&
            $pos->getZ() >= $min->getZ() && $pos->getZ() <= $max->getZ();
    }

}