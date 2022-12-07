<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use Vigotech\EventCollection;
use Vigotech\Group;

use function Lambdish\Phunctional\sort;

final class EventFetcher
{
    private EventCollection $events;

    private array $eventFetchers;

    public function __construct(EventFetcherTypable ...$eventFetchers)
    {
        $this->events = new EventCollection();
        $this->eventFetchers = [];

        foreach ($eventFetchers as $eventFetcher) {
            $this->eventFetchers[$eventFetcher->type()] = $eventFetcher;
        }
    }

    public function fetch(Group $group, array $eventType): void
    {
        $this->addEvents($this->eventFetchers[$eventType['type']]->fetch($group, $eventType));
    }

    private function addEvents(EventCollection $events): void
    {
        foreach ($events->getItems() as $event) {
            $this->events->add($event);
        }
    }

    public function getEvents(): EventCollection
    {
        return new EventCollection(sort($this->sortDateAsc(), $this->events));
    }

    private function sortDateAsc(): callable
    {
        return function ($event1, $event2) {
            return $event1->getDate() > $event2->getDate();
        };
    }
}
