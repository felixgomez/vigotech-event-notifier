<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Vigotech\Events;
use Vigotech\Service\DateFormatter;

/**
 * Class EventNotifierTwitter
 * @package Vigotech\Service\EventNotifier
 */
final class EventNotifierTwitter extends Client implements EventNotifierTypable
{
    use EventNotifierPreviewTrait;
    /**
     * @var DateFormatter
     */
    private $dateFormatter;

    /**
     * EventNotifierTwitter constructor.
     * @param array $twitterConfig
     * @param HandlerStack $handlerStack
     * @param DateFormatter $dateFormatter
     */
    public function __construct(array $twitterConfig, HandlerStack $handlerStack, DateFormatter $dateFormatter)
    {
        $this->dateFormatter = $dateFormatter;

        parent::__construct(
            [
                'base_uri' => $twitterConfig['base_uri'],
                'handler' => $handlerStack,
            ]
        );
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return 'twitter';
    }

    /**
     * @param Events $events
     * @param bool $preview
     */
    public function notifyWeekly(Events $events, bool $preview): void
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

    /**
     * @param Events $events
     */
    public function notifyDaily(Events $events, bool $preview): void
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

    /**
     * @param Events $events
     */
    public function notifyUpcoming(Events $events, bool $preview): void
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

    /**
     * @param $twitterUrl
     * @return bool|string
     */
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

    /**
     * @param string $status
     * @param bool $preview
     * @throws EventNotifierException
     */
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
            throw new EventNotifierException($this->type, $e->getMessage());
        }
    }
}
