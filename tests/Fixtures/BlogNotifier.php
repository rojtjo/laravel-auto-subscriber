<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber\Fixtures;

use Rojtjo\LaravelAutoSubscriber\AutoSubscriber;

final class BlogNotifier
{
    use AutoSubscriber;

    private array $exclude;

    public function __construct(array $exclude = [])
    {
        $this->exclude = $exclude;
    }

    public function exclude(): array
    {
        return $this->exclude;
    }

    public function sendNewReplyNotification(NewReply $event): void
    {
    }

    public function sendAnotherNewReplyNotification(NewReply $event): void
    {
    }

    public function sendReplyChangedNotification(ReplyUpdated|ReplyDeleted $event): void
    {
    }

    private function privateMethodsAreIgnored(NewReply $event): void
    {
    }

    private function protectedMethodsAreIgnored(NewReply $event): void
    {
    }

    public function multipleParametersIsInvalid(NewReply $event, string $foo): void
    {
    }

    public function scalarParameterType(string $event): void
    {
    }
}
