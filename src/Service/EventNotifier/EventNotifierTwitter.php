<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Vigotech\EventCollection;
use Vigotech\Service\DateFormatter;

final class EventNotifierTwitter extends Client implements EventNotifierTypable
{
    use EventNotifierPreviewTrait;

    private DateFormatter $dateFormatter;

    public function __construct(
        array $twitterConfig,
        HandlerStack $handlerStack,
        DateFormatter $dateFormatter
    ) {
        $this->dateFormatter = $dateFormatter;

        parent::__construct(
            [
                'base_uri' => $twitterConfig['base_uri'],
                'handler' => $handlerStack,
            ]
        );
    }

    public function type(): string
    {
        return 'twitter';
    }

    public function notifyWeekly(EventCollection $events, bool $preview): void
    {
        $totalEvents = count($events);

        if ($totalEvents > 0) {
            $status = (1 === $totalEvents) ?
                "🔴 Hai {$totalEvents} evento esta semana." :
                "🔴 Hai {$totalEvents} eventos esta semana.";

            $status .= PHP_EOL.'ℹ en vigotech.org.';
            $this->publish($status, $preview);
        }
    }

    public function notifyDaily(EventCollection $events, bool $preview): void
    {
        foreach ($events as $event) {
            $twitterAccount = $this->getAccountFromTwitterUrl($event->getGroup()->getLinks()['twitter']);

            if (empty($twitterAccount)) {
                $twitterAccount = $event->getGroup()->getName();
            }

            $status = sprintf(
                '📅 Hoxe o evento de %s'.PHP_EOL.'▶️️ «%s» comeza ás 🕗%s.'.PHP_EOL.'ℹ %s ou en vigotech.org',
                $twitterAccount,
                $event->getName(),
                $this->dateFormatter->getHour($event->getDate()),
                $event->getLink()
            );

            $this->publish($status, $preview);
        }
    }

    public function notifyUpcoming(EventCollection $events, bool $preview): void
    {
        foreach ($events as $event) {
            $twitterAccount = $this->getAccountFromTwitterUrl($event->getGroup()->getLinks()['twitter']);

            if (empty($twitterAccount)) {
                $twitterAccount = $event->getGroup()->getName();
            }

            $status = sprintf(
                '🚨🚨🚨 O evento de %s'.PHP_EOL.'▶️️ «%s» comeza en breve (🕗%s).'.PHP_EOL.'ℹ %s ou en vigotech.org',
                $twitterAccount,
                $event->getName(),
                $this->dateFormatter->getHour($event->getDate()),
                $event->getLink()
            );

            $this->publish($status, $preview);
        }
    }

    private function getAccountFromTwitterUrl(string $twitterUrl): string
    {
        $result = preg_match(
            "|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|",
            $twitterUrl,
            $matches
        );

        if (1 === $result) {
            $twitterAccount = '@'.$matches[3];

            return $twitterAccount;
        } else {
            return '';
        }
    }

    private function publish(string $status, bool $preview): void
    {
        if ($preview) {
            $this->preview($status);

            return;
        }

        try {
            $this->post(
                'statuses/update.json',
                [
                    'auth' => 'oauth',
                    'form_params' => [
                        'status' => $status,
                    ],
                ]
            );
        } catch (\Exception $e) {
            throw new EventNotifierException($this->type(), $e->getMessage());
        }
    }
}
