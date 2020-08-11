<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use DateTimeImmutable;
use GuzzleHttp\Client;
use Vigotech\Event;
use Vigotech\Events;
use Vigotech\Group;

/**
 * Class EventFetcherMeetup
 * @package Vigotech\Service\EventFetcher
 */
final class EventFetcherMeetup extends Client implements EventFetcherTypable
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
            $response = $this->get("http://api.meetup.com/{$eventType['meetupid']}/events");
        } catch (\Exception $exception) {
            throw new EventFetcherException($this->type(), $exception->getMessage());
        }

        $parsedResponse = json_decode($response->getBody()->getContents(), true);

        $events = new Events();

        foreach ($parsedResponse as $item) {
            $date = (new DateTimeImmutable())->setTimestamp($item['time'] / 1000);
            $event = new Event();
            $event
                ->setName($item['name'])
                ->setDate($date)
                ->setGroup($group)
                ->setLink($item['link'])
                ->setType($this->type());

            $events->add($event);
        }

        return $events;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'meetup';
    }
}
