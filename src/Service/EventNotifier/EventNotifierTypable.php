<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use Vigotech\Events;

/**
 * Interface EventNotifierTypable
 * @package Vigotech\Service\EventNotifier
 */
interface EventNotifierTypable
{
    public function notifyWeekly(Events $events, bool $preview): void;

    public function notifyDaily(Events $events, bool $preview): void;

    public function notifyUpcoming(Events $events, bool $preview): void;

    public function type(): string;
}
