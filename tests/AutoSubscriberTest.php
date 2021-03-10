<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use PHPUnit\Framework\TestCase;

final class AutoSubscriberTest extends TestCase
{
    private DispatcherFake $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher = new DispatcherFake();
    }

    /** @test */
    public function it_subscribes_to_events(): void
    {
        $subscriber = new BlogNotifier();
        $subscriber->subscribe($this->dispatcher);

        $this->dispatcher->assertHasListener(BlogNotifier::class, 'sendNewReplyNotification', NewReply::class);
        $this->dispatcher->assertHasListener(BlogNotifier::class, 'sendAnotherNewReplyNotification', NewReply::class);
        $this->dispatcher->assertMissingListener(BlogNotifier::class, 'subscribe');
        $this->dispatcher->assertMissingListener(BlogNotifier::class, 'privateMethodsAreIgnored');
        $this->dispatcher->assertMissingListener(BlogNotifier::class, 'protectedMethodsAreIgnored');
        $this->dispatcher->assertMissingListener(BlogNotifier::class, 'multipleParametersIsInvalid');
        $this->dispatcher->assertMissingListener(BlogNotifier::class, 'scalarParameterType');
    }

    /** @test */
    public function it_exclude_handlers(): void
    {
        $subscriber = new BlogNotifier([
            'sendAnotherNewReplyNotification',
        ]);

        $subscriber->subscribe($this->dispatcher);

        $this->dispatcher->assertMissingListener(BlogNotifier::class, 'sendAnotherNewReplyNotification', NewReply::class);
    }
}

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

final class NewReply
{
}
