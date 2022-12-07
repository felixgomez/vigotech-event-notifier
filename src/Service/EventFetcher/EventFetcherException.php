<?php

namespace Vigotech\Service\EventFetcher;

use Throwable;

class EventFetcherException extends \Exception
{
    private string $type;

    public function __construct(string $type, string $message = '', int $code = 0, Throwable $previous = null)
    {
        $this->type = $type;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
