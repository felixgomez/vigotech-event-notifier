<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use DateTimeImmutable;
use GuzzleHttp\Client;
use Vigotech\Event;
use Vigotech\EventCollection;
use Vigotech\Group;

/**
 * Class EventFetcherJson.
 */
final class EventFetcherJson extends Client implements EventFetcherTypable
{
    /**
     * @throws \Exception
     */
    public function fetch(Group $group, array $eventType): EventCollection
    {
        try {
            $eventFromJson = json_decode($this->get($eventType['source'])->getBody()->getContents());
        } catch (\Exception $exception) {
            throw new EventFetcherException($this->type(), $exception->getMessage());
        }

        $events = new EventCollection();

        $date = (new DateTimeImmutable())->setTimestamp($eventFromJson->date / 1000);

        $event = new Event();
        $event
            ->setName($eventFromJson->title)
            ->setDate($date)
            ->setGroup($group)
            ->setLink($eventFromJson->url)
            ->setType($this->type());

        $events->add($event);

        return $events;
    }

    public function type(): string
    {
        return 'json';
    }
}
