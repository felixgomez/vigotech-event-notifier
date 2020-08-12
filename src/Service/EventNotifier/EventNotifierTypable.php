<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use Vigotech\EventCollection;

/**
 * Interface EventNotifierTypable.
 */
interface EventNotifierTypable
{
    public function notifyWeekly(EventCollection $events, bool $preview): void;

    public function notifyDaily(EventCollection $events, bool $preview): void;

    public function notifyUpcoming(EventCollection $events, bool $preview): void;

    public function type(): string;
}
