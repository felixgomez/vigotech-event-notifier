<?php

declare(strict_types=1);

namespace Vigotech\Service\EventFetcher;

use DateTimeInmutable;
use GuzzleHttp\Client;
use Vigotech\Event;
use Vigotech\Events;
use Vigotech\Group;

/**
 * Class EventFetcherEventbrite
 * @package Vigotech\Service\EventFetcher
 */
final class EventFetcherEventbrite extends Client implements EventFetcherTypable
{
    private $oauth_token;

    /**
     * EventFetcherEventbrite constructor.
     * @param string $oauth_token
     */
    public function __construct(string $oauth_token)
    {
        $this->oauth_token = $oauth_token;
        parent::__construct();
    }

    /**
     * @param Group $group
     * @param array $eventType
     * @return Events
     * @throws \Exception
     */
    public function fetch(Group $group, array $eventType): Events
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

        $events = new Events();

        foreach ($parsedResponse['events'] as $item) {
            $date = new DateTimeInmutable($item['start']['utc']);

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

    /**
     * @return string
     */
    public function type(): string
    {
        return 'eventbrite';
    }
}
