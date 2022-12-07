<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use Exception;
use GuzzleHttp\Client;
use Vigotech\EventCollection;
use Vigotech\Service\DateFormatter;

final class EventNotifierTelegram extends Client implements EventNotifierTypable
{
    use EventNotifierPreviewTrait;

    private string $chat_id;

    private string $token;

    private DateFormatter $dateFormatter;

    private bool $disableNotification;

    public function __construct(array $config, DateFormatter $dateFormatter)
    {
        $this->chat_id = $config['chat_id'];
        $this->token = $config['token_bot'];
        $this->dateFormatter = $dateFormatter;
        $this->disableNotification = boolval($config['disable_notification']);
        parent::__construct($config);
    }

    public function type(): string
    {
        return 'telegram';
    }

    /**
     * @throws EventNotifierException
     */
    public function notifyWeekly(EventCollection $events, bool $preview): void
    {
        $totalEvents = count($events);

        if ($totalEvents > 0) {
            $text = (1 === $totalEvents) ?
                "⬇️⬇️⬇️ Hai *{$totalEvents}* evento esta semana ⬇️⬇️⬇️" :
                "⬇️⬇️⬇️ Hai *{$totalEvents}* eventos esta semana ⬇️⬇️⬇️";

            $this->publish($text, $preview);

            foreach ($events as $event) {
                $text = sprintf(
                    '%s'.PHP_EOL.'📅 _%s_.'.PHP_EOL.'▶️️ *«%s»*'.PHP_EOL.'ℹ [aquí](%s) ou en vigotech.org',
                    $event->getGroup()->getName(),
                    $this->dateFormatter->format($event->getDate()),
                    $event->getName(),
                    $event->getLink()
                );

                $this->publish($text, $preview);
            }
        }
    }

    /**
     * @throws EventNotifierException
     */
    public function notifyDaily(EventCollection $events, bool $preview): void
    {
        $totalEvents = count($events);

        if ($totalEvents > 0) {
            $text = (1 === $totalEvents) ?
                "⬇️⬇️⬇️ 📅 Hoxe hai *{$totalEvents}* evento ⬇️⬇️⬇️" :
                "⬇️⬇️⬇️ 📅 Hoxe hai *{$totalEvents}* eventos ⬇️⬇️⬇️";

            $this->publish($text, $preview);

            foreach ($events as $event) {
                $text = sprintf(
                    '*%s*'.PHP_EOL.'📅 _%s_.'.PHP_EOL.'▶️️ *«%s»*'.PHP_EOL.'ℹ [aquí](%s) ou en vigotech.org',
                    $event->getGroup()->getName(),
                    $this->dateFormatter->format($event->getDate()),
                    $event->getName(),
                    $event->getLink()
                );

                $this->publish($text, $preview);
            }
        }
    }

    public function notifyUpcoming(EventCollection $events, bool $preview): void
    {
        $totalEvents = count($events);

        if ($totalEvents > 0) {
            $text = (1 === $totalEvents) ?
                '🚨🚨🚨 Evento que comeza pronto ⬇️⬇️⬇' :
                '🚨🚨🚨 Eventos que comezan pronto⬇️⬇️⬇';

            $this->publish($text, $preview);

            foreach ($events as $event) {
                $text = sprintf(
                    '*%s*'.PHP_EOL.'🚨 «%s»'.PHP_EOL.'🕗 Comeza en breve (as %s).'.PHP_EOL.'ℹ %s ou en vigotech.org',
                    $event->getGroup()->getName(),
                    $event->getName(),
                    $this->dateFormatter->getHour($event->getDate()),
                    $event->getLink()
                );

                $this->publish($text, $preview);
            }
        }
    }

    private function publish(string $text, bool $preview): void
    {
        if ($preview) {
            $this->preview($text);

            return;
        }

        try {
            $this->post(
                'https://api.telegram.org/bot'.$this->token.'/sendMessage',
                [
                    'json' => [
                        'chat_id' => $this->chat_id,
                        'text' => $text,
                        'parse_mode' => 'markdown',
                        'disable_notification' => $this->disableNotification,
                    ],
                ]
            );
        } catch (Exception $e) {
            throw new EventNotifierException($this->type(), $e->getMessage());
        }
    }
}
