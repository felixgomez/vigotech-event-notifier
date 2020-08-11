<?php

namespace Vigotech\Service\EventFetcher;

use Throwable;

class EventFetcherException extends \Exception
{
    /**
     * @var string
     */
    private $type;

    public function __construct(string $type, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->type = $type;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }
}
