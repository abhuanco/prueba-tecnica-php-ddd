<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Event {

    interface EventDispatcherInterface
    {
        public function dispatch(object $event): void;
    }
}