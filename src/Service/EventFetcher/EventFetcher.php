<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use function Lambdish\Phunctional\sort;
use Vigotech\EventCollection;
use Vigotech\Group;

final class EventFetcher
{
    /**
     * @var EventCollection
     */
    private $events;

    /**
     * @var array
     */
    private $eventFetchers;

    /**
     * EventFetcher constructor.
     *
     * @param EventFetcherTypable ...$eventFetchers
     */
    public function __construct(EventFetcherTypable ...$eventFetchers)
    {
        $this->events = new EventCollection();
        $this->eventFetchers = [];

        foreach ($eventFetchers as $eventFetcher) {
            $this->eventFetchers[$eventFetcher->type()] = $eventFetcher;
        }
    }

    /**
     * Fetch group events by type.
     */
    public function fetch(Group $group, array $eventType): void
    {
        $this->addEvents($this->eventFetchers[$eventType['type']]->fetch($group, $eventType));
    }

    /**
     * Add events.
     */
    private function addEvents(EventCollection $events): void
    {
        foreach ($events->getItems() as $event) {
            $this->events->add($event);
        }
    }

    /**
     * Get all events sorted.
     */
    public function getEvents(): EventCollection
    {
        return new EventCollection(sort($this->sortDateAsc(), $this->events));
    }

    /**
     * Return sort asc by date function.
     */
    private function sortDateAsc(): callable
    {
        return function ($event1, $event2) {
            return $event1->getDate() > $event2->getDate();
        };
    }
}
