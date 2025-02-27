<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Event {

    final class SimpleEventDispatcher implements EventDispatcherInterface
    {
        /**
         * @var array<string, callable[]>
         */
        private array $listeners = [];

        public function addListener(string $eventClass, callable $listener): void
        {
            $this->listeners[$eventClass][] = $listener;
        }

        public function dispatch(object $event): void
        {
            $eventClass = get_class($event);
            if (isset($this->listeners[$eventClass])) {
                foreach ($this->listeners[$eventClass] as $listener) {
                    $listener($event);
                }
            }
        }
    }
}