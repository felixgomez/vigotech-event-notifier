<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

use Throwable;

class EventNotifierException extends \Exception
{
    private $channel;

    public function __construct(string $channel, string $message = '', int $code = 0, Throwable $previous = null)
    {
        $this->channel = $channel;
        parent::__construct($message, $code, $previous);
    }

    public function getChannel(): string
    {
        return $this->channel;
    }
}
