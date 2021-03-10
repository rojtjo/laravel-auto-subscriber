<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use Illuminate\Contracts\Events\Dispatcher;

trait AutoSubscriber
{
    public function subscribe(Dispatcher $events): void
    {
        $exclude = array_merge(['subscribe'], $this->exclude());

        FindListeners::for($this)
            ->except($exclude)
            ->each(function (string $event, string $handler) use ($events) {
                $events->listen($event, [$this, $handler]);
            });
    }

    protected function exclude(): array
    {
        return [];
    }
}
