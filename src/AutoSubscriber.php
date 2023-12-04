<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use Illuminate\Contracts\Events\Dispatcher;

trait AutoSubscriber
{
    public function subscribe(Dispatcher $dispatcher): void
    {
        $exclude = array_merge(['subscribe'], $this->exclude());

        FindListeners::for($this)
            ->except($exclude)
            ->each(function (array $events, string $handler) use ($dispatcher) {
                foreach ($events as $event) {
                    $dispatcher->listen($event, [$this, $handler]);
                }
            });
    }

    protected function exclude(): array
    {
        return [];
    }
}
