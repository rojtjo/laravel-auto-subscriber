<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber\Fixtures;

use Rojtjo\LaravelAutoSubscriber\AutoSubscriber;

final readonly class BlogNotifier
{
    use AutoSubscriber;

    /**
     * @param  list<string>  $exclude
     */
    public function __construct(
        private array $exclude = [],
    )
    {
    }

    /**
     * @return list<string>
     */
    public function exclude(): array
    {
        return $this->exclude;
    }

    public function sendNewReplyNotification(NewReply $event): void {}

    public function sendAnotherNewReplyNotification(NewReply $event): void {}

    public function sendReplyChangedNotification(ReplyUpdated|ReplyDeleted $event): void {}

    /**
     * @phpstan-ignore method.unused
     */
    private function privateMethodsAreIgnored(NewReply $event): void {}

    /**
     * @phpstan-ignore method.unused
     */
    private function protectedMethodsAreIgnored(NewReply $event): void {}

    public function multipleParametersIsInvalid(NewReply $event, string $foo): void {}

    public function scalarParameterType(string $event): void {}
}
