<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use Vigotech\EventCollection;

/**
 * Class EventNotifier.
 */
final class EventNotifier
{
    private bool $previewMode = false;

    private array $eventNotifiers;

    public function __construct(EventNotifierTypable ...$eventNotifiers)
    {
        $this->eventNotifiers = [];

        foreach ($eventNotifiers as $eventNotifier) {
            $this->eventNotifiers[$eventNotifier->type()] = $eventNotifier;
        }
    }

    public function notifyWeekly(EventCollection $events): void
    {
        foreach ($this->eventNotifiers as $eventNotifier) {
            $eventNotifier->notifyWeekly($events, $this->previewMode);
        }
    }

    public function notifyDaily(EventCollection $events): void
    {
        foreach ($this->eventNotifiers as $eventNotifier) {
            $eventNotifier->notifyDaily($events, $this->previewMode);
        }
    }

    public function notifyUpcoming(EventCollection $events): void
    {
        foreach ($this->eventNotifiers as $eventNotifier) {
            $eventNotifier->notifyUpcoming($events, $this->previewMode);
        }
    }

    public function isPreviewMode(): bool
    {
        return $this->previewMode;
    }

    public function setPreviewMode(bool $previewMode): void
    {
        $this->previewMode = $previewMode;
    }
}
