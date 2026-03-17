<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert;

final class DispatcherFake implements Dispatcher
{
    /**
     * @var list<array{event: string|list<string>, subscriber: object, handler: callable}>
     */
    private array $listeners = [];

    /**
     * @param  string  $events
     * @param  array{object, string}  $listener
     */
    public function listen($events, $listener = null)
    {
        [$subscriber, $handler] = $listener;

        $this->listeners[] = [
            'event' => $events,
            'subscriber' => $subscriber,
            'handler' => $handler,
        ];
    }

    public function assertHasListener(string $subscriber, string $handler, ?string $event = null): void
    {
        Assert::assertTrue(
            $this->hasListener($subscriber, $handler, $event),
            "Expected listener to be registered: {$subscriber}::{$handler} event: {$event}",
        );
    }

    public function assertMissingListener(string $subscriber, string $handler, ?string $event = null): void
    {
        Assert::assertFalse(
            $this->hasListener($subscriber, $handler, $event),
            "Expected listener to be missing: {$subscriber}::{$handler} event: {$event}",
        );
    }

    private function hasListener(string $subscriber, string $handler, ?string $event): bool
    {
        return collect($this->listeners)
            ->where('subscriber', $subscriber)
            ->where('handler', $handler)
            ->when($event, fn (Collection $listeners) => $listeners->where('event', $event))
            ->isNotEmpty();
    }

    /**
     * @phpstan-ignore return.missing
     */
    public function hasListeners($eventName) {}

    public function subscribe($subscriber) {}

    public function until($event, $payload = []) {}

    /**
     * @phpstan-ignore missingType.iterableValue, return.missing
     */
    public function dispatch($event, $payload = [], $halt = false) {}

    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function push($event, $payload = []) {}

    public function flush($event) {}

    public function forget($event) {}

    public function forgetPushed() {}
}
