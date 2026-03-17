<?php

declare(strict_types=1);

namespace Rojtjo\LaravelAutoSubscriber;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Rojtjo\LaravelAutoSubscriber\Fixtures\BlogNotifier;
use Rojtjo\LaravelAutoSubscriber\Fixtures\NewReply;
use Rojtjo\LaravelAutoSubscriber\Fixtures\ReplyDeleted;
use Rojtjo\LaravelAutoSubscriber\Fixtures\ReplyUpdated;

final class AutoSubscriberTest extends TestCase
{
    #[Test]
    public function it_subscribes_to_events(): void
    {
        $dispatcher = new DispatcherFake();

        $subscriber = new BlogNotifier();
        $subscriber->subscribe($dispatcher);

        $dispatcher->assertHasListener(BlogNotifier::class, 'sendNewReplyNotification', NewReply::class);
        $dispatcher->assertHasListener(BlogNotifier::class, 'sendAnotherNewReplyNotification', NewReply::class);
        $dispatcher->assertHasListener(BlogNotifier::class, 'sendReplyChangedNotification', ReplyUpdated::class);
        $dispatcher->assertHasListener(BlogNotifier::class, 'sendReplyChangedNotification', ReplyDeleted::class);
        $dispatcher->assertMissingListener(BlogNotifier::class, 'subscribe');
        $dispatcher->assertMissingListener(BlogNotifier::class, 'privateMethodsAreIgnored');
        $dispatcher->assertMissingListener(BlogNotifier::class, 'protectedMethodsAreIgnored');
        $dispatcher->assertMissingListener(BlogNotifier::class, 'multipleParametersIsInvalid');
        $dispatcher->assertMissingListener(BlogNotifier::class, 'scalarParameterType');
    }

    #[Test]
    public function it_exclude_handlers(): void
    {
        $dispatcher = new DispatcherFake();

        $subscriber = new BlogNotifier([
            'sendAnotherNewReplyNotification',
        ]);

        $subscriber->subscribe($dispatcher);

        $dispatcher->assertMissingListener(BlogNotifier::class, 'sendAnotherNewReplyNotification', NewReply::class);
    }
}
