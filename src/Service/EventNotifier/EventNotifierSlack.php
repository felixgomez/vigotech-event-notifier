<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use GuzzleHttp\Client;
use Vigotech\Events;
use Vigotech\Service\DateFormatter;

/**
 * Class EventNotifierSlack.
 */
final class EventNotifierSlack extends Client implements EventNotifierTypable
{
    use EventNotifierPreviewTrait;

    /**
     * @var string
     */
    private $webhook_url;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $icon_url;

    /**
     * @var DateFormatter
     */
    private $dateFormatter;

    /**
     * @var bool
     */
    private $show_group_thumbs;

    public function type(): string
    {
        return 'slack';
    }

    /**
     * EventNotifierSlack constructor.
     */
    public function __construct(array $slackConfig, DateFormatter $dateFormatter)
    {
        $this->webhook_url = $slackConfig['webhook_url'];
        $this->name = $slackConfig['name'];
        $this->icon_url = strtr($slackConfig['icon_url'], [' ' => '%20']);
        $this->dateFormatter = $dateFormatter;
        $this->show_group_thumbs = $slackConfig['show_group_thumbs'];

        parent::__construct();
    }

    /**
     * @throws EventNotifierException
     */
    public function notifyWeekly(Events $events, bool $preview): void
    {
        $totalEvents = count($events);

        if ($totalEvents > 0) {
            $pretext = (1 === $totalEvents) ?
                "⬇️⬇️⬇️ Hai *{$totalEvents}* evento esta semana ⬇️⬇️⬇️" :
                "⬇️⬇️⬇️ Hai *{$totalEvents}* eventos esta semana ⬇️⬇️⬇️";

            $payload = $this->composePayload($pretext, $events);

            $this->publishPayload($payload, $preview);
        }
    }

    public function notifyDaily(Events $events, bool $preview): void
    {
        $totalEvents = count($events);

        if ($totalEvents > 0) {
            $pretext = $pretext = (1 === $totalEvents) ?
                "⬇️⬇️⬇️ Hoxe hai *{$totalEvents}* evento ⬇️⬇️⬇️" :
                "⬇️⬇️⬇️ Hoxe hai *{$totalEvents}* eventos ⬇️⬇️⬇️";

            $payload = $this->composePayload($pretext, $events);

            $this->publishPayload($payload, $preview);
        }
    }

    public function notifyUpcoming(Events $events, bool $preview): void
    {
        $totalEvents = count($events);

        if ($totalEvents > 0) {
            $pretext = $pretext = (1 === $totalEvents) ?
                '🚨🚨🚨 Evento que comeza en breve 🚨🚨🚨' :
                '🚨🚨🚨 Eventos que comezan en breve 🚨🚨🚨';

            $payload = $this->composePayload($pretext, $events);

            $this->publishPayload($payload, $preview);
        }
    }

    /**
     * @param $pretext
     * @param $events
     */
    private function composePayload(string $pretext, Events $events): array
    {
        $attachments = [];

        foreach ($events as $event) {
            $attachment = [
                'color' => '#D00000',
                'author_name' => $event->getGroup()->getName(),
                'author_link' => $event->getGroup()->getLinks()['web'],
                'author_icon' => $event->getGroup()->getLogo(),
                'text' => $this->dateFormatter->format($event->getDate()),
                'title' => $event->getName(),
                'title_link' => $event->getLink(),
                'fields' => [],
            ];

            if ($this->show_group_thumbs) {
                $attachment['thumb_url'] = $event->getGroup()->getLogo();
            }

            $attachments[] = $attachment;
        }

        $payload = [
            'username' => $this->name,
            'icon_url' => $this->icon_url,
            'text' => $pretext,
            'attachments' => $attachments,
        ];

        return $payload;
    }

    /**
     * @param $payload
     *
     * @throws EventNotifierException
     */
    private function publishPayload($payload, bool $preview): void
    {
        if ($preview) {
            $this->preview($payload['text']);

            return;
        }

        try {
            $this->post(
                $this->webhook_url,
                [
                    'json' => $payload,
                ]
            );
        } catch (\Exception $e) {
            throw new EventNotifierException($this->type(), $e->getMessage());
        }
    }
}
