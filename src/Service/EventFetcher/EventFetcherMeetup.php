<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use DateTimeImmutable;
use GuzzleHttp\Client;
use Vigotech\Event;
use Vigotech\EventCollection;
use Vigotech\Group;

/**
 * Class EventFetcherMeetup.
 */
final class EventFetcherMeetup extends Client implements EventFetcherTypable
{
    /**
     * @throws \Exception
     */
    public function fetch(Group $group, array $eventType): EventCollection
    {
        try {
            $response = $this->get("http://api.meetup.com/{$eventType['meetupid']}/events");
        } catch (\Exception $exception) {
            throw new EventFetcherException($this->type(), $exception->getMessage());
        }

        $parsedResponse = json_decode($response->getBody()->getContents(), true);

        $events = new EventCollection();

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

    public function type(): string
    {
        return 'meetup';
    }
}
