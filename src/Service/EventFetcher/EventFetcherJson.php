<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use GuzzleHttp\Client;
use Vigotech\Event;
use Vigotech\Events;
use Vigotech\Group;
use DateTimeImmutable;

/**
 * Class EventFetcherJson
 * @package Vigotech\Service\EventFetcher
 */
final class EventFetcherJson extends Client implements EventFetcherTypable
{
    /**
     * @param Group $group
     * @param array $eventType
     * @return Events
     * @throws \Exception
     */
    public function fetch(Group $group, array $eventType): Events
    {
        try {
            $eventFromJson = json_decode($this->get($eventType['source'])->getBody()->getContents());
        } catch (\Exception $exception) {
            throw new EventFetcherException($this->type(), $exception->getMessage());
        }

        $events = new Events();

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

    /**
     * @return string
     */
    public function type(): string
    {
        return 'json';
    }
}
