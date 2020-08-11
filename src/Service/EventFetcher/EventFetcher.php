<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use Vigotech\Events;
use Vigotech\Group;
use function Lambdish\Phunctional\sort;

final class EventFetcher
{

    /**
     * @var Events
     */
    private $events;

    /**
     * @var array
     */
    private $eventFetchers;

    /**
     * EventFetcher constructor.
     * @param EventFetcherTypable ...$eventFetchers
     */
    public function __construct(EventFetcherTypable ...$eventFetchers)
    {
        $this->events = new Events();
        $this->eventFetchers = [];

        foreach ($eventFetchers as $eventFetcher) {
            $this->eventFetchers[$eventFetcher->type()] = $eventFetcher;
        }
    }

    /**
     * Fetch group events by type
     * @param Group $group
     * @param array $eventType
     */
    public function fetch(Group $group, array $eventType): void
    {
        $this->addEvents($this->eventFetchers[$eventType['type']]->fetch($group, $eventType));
    }

    /**
     * Add events
     * @param Events $events
     */
    private function addEvents(Events $events): void
    {
        foreach ($events->getItems() as $event) {
            $this->events->add($event);
        }
    }

    /**
     * Get all events sorted
     * @return Events
     */
    public function getEvents(): Events
    {
        return new Events(sort($this->sortDateAsc(), $this->events));
    }

    /**
     * Return sort asc by date function
     * @return callable
     */
    private function sortDateAsc(): callable
    {
        return function ($event1, $event2) {
            return $event1->getDate() > $event2->getDate();
        };
    }
}
