<?php
declare(strict_types=1);

namespace Tests\Unit\User\Infrastructure\Event {

    use App\User\Infrastructure\Event\SimpleEventDispatcher;
    use PHPUnit\Framework\TestCase;

    class DummyEvent
    {
    }

    final class SimpleEventDispatcherTest extends TestCase
    {
        public function testDispatchCallsRegisteredListener(): void
        {
            $dispatcher = new SimpleEventDispatcher();
            $event = new DummyEvent();
            $called = false;

            $dispatcher->addListener(DummyEvent::class, function ($e) use (&$called, $event) {
                $called = true;
                $this->assertSame($event, $e);
            });

            $dispatcher->dispatch($event);
            $this->assertTrue($called, 'El listener registrado no fue llamado al despachar el evento.');
        }

        public function testDispatchDoesNotCallListenerForDifferentEvent(): void
        {
            $dispatcher = new SimpleEventDispatcher();
            $event = new DummyEvent();
            $called = false;

            $dispatcher->addListener(\stdClass::class, function ($e) use (&$called) {
                $called = true;
            });

            $dispatcher->dispatch($event);
            $this->assertFalse($called, 'El listener no registrado para este evento no debe ser llamado.');
        }

        public function testMultipleListenersAreCalled(): void
        {
            $dispatcher = new SimpleEventDispatcher();
            $event = new DummyEvent();
            $callCount = 0;

            $dispatcher->addListener(DummyEvent::class, function ($e) use (&$callCount) {
                $callCount++;
            });
            $dispatcher->addListener(DummyEvent::class, function ($e) use (&$callCount) {
                $callCount++;
            });

            $dispatcher->dispatch($event);
            $this->assertSame(2, $callCount, 'No se llamaron todos los listeners registrados para el evento.');
        }
    }
}