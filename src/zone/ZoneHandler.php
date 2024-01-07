<?php

declare(strict_types=1);

namespace phuongaz\zoneevent\zone;

final class ZoneHandler {

    public function __construct(
        private readonly string   $eventClass,
        private readonly \Closure $closure,
        private readonly int      $priority = 0,
        private readonly bool $ignoreCancelled = false
    ){}

    public function getEventClass() : string {
        return $this->eventClass;
    }

    public function getClosure() : \Closure {
        return $this->closure;
    }

    public function getPriority() : int {
        return $this->priority;
    }

    public function isIgnoreCancelled() : bool {
        return $this->ignoreCancelled;
    }
}