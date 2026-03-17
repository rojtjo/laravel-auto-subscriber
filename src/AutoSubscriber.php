<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Collection;

trait AutoSubscriber
{
    public function subscribe(Dispatcher $dispatcher): void
    {
        FindListeners::for($this)
            ->except($this->exclude())
            ->each(fn (Collection $events, string $handler) => $events
                ->each(fn (string $event) => $dispatcher
                    ->listen($event, [static::class, $handler])));
    }

    protected function exclude(): array
    {
        return [];
    }
}
