<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use Vigotech\EventCollection;
use Vigotech\Group;

/**
 * Interface EventFetcherTypable.
 */
interface EventFetcherTypable
{
    public function fetch(Group $group, array $eventType): EventCollection;

    public function type(): string;
}
