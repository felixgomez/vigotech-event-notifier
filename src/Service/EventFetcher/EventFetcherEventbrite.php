<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use DateTimeImmutable;
use GuzzleHttp\Client;
use Vigotech\Event;
use Vigotech\EventCollection;
use Vigotech\Group;

final class EventFetcherEventbrite extends Client implements EventFetcherTypable
{
    private string $oauth_token;

    public function __construct(string $oauth_token)
    {
        $this->oauth_token = $oauth_token;
        parent::__construct();
    }

    public function fetch(Group $group, array $eventType): EventCollection
    {
        try {
            $response = $this->get(
                'https://www.eventbriteapi.com/v3/events/search/',
                [
                    'query' => [
                        'organizer.id' => $eventType['eventbriteid'],
                    ],
                    'headers' => [
                        'Authorization' => "Bearer {$this->oauth_token}",
                        'Content-Type' => 'application/json',
                    ],
                ]
            );
        } catch (\Exception $exception) {
            throw new EventFetcherException($this->type(), $exception->getMessage());
        }

        $parsedResponse = json_decode($response->getBody()->getContents(), true);

        $events = new EventCollection();

        foreach ($parsedResponse['events'] as $item) {
            $date = new DateTimeImmutable($item['start']['utc']);

            $event = new Event();
            $event
                ->setName($item['name']['text'])
                ->setDate($date)
                ->setGroup($group)
                ->setLink($item['url'])
                ->setType($this->type());

            $events->add($event);
        }

        return $events;
    }

    public function type(): string
    {
        return 'eventbrite';
    }
}
