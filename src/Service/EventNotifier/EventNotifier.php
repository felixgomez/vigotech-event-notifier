<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use Vigotech\Events;

/**
 * Class EventNotifier
 * @package Vigotech\Service\EventNotifier
 */
final class EventNotifier
{
    private $previewMode = false;

    /**
     * @var array
     */
    private $eventNotifiers;

    /**
     * EventNotifier constructor.
     * @param EventNotifierTypable ...$eventNotifiers
     */
    public function __construct(EventNotifierTypable ...$eventNotifiers)
    {
        $this->eventNotifiers = [];

        foreach ($eventNotifiers as $eventNotifier) {
            $this->eventNotifiers[$eventNotifier->type()] = $eventNotifier;
        }
    }

    /**
     * @param Events $events
     */
    public function notifyWeekly(Events $events): void
    {
        foreach ($this->eventNotifiers as $eventNotifier) {
            $eventNotifier->notifyWeekly($events, $this->previewMode);
        }
    }

    /**
     * @param Events $events
     */
    public function notifyDaily(Events $events): void
    {
        foreach ($this->eventNotifiers as $eventNotifier) {
            $eventNotifier->notifyDaily($events, $this->previewMode);
        }
    }

    /**
     * @param Events $events
     */
    public function notifyUpcoming(Events $events): void
    {
        foreach ($this->eventNotifiers as $eventNotifier) {
            $eventNotifier->notifyUpcoming($events, $this->previewMode);
        }
    }

    /**
     * @return bool
     */
    public function isPreviewMode(): bool
    {
        return $this->previewMode;
    }

    /**
     * @param bool $previewMode
     */
    public function setPreviewMode(bool $previewMode): void
    {
        $this->previewMode = $previewMode;
    }
}
