<?php

declare(strict_types=1);

namespace Vigotech;

use DateTime;

/**
 * Class EventCollection.
 */
final class EventCollection extends Collection
{
    /**
     * @return EventCollection
     */
    public function filterMonth(): ?EventCollection
    {
        return new EventCollection($this->filter(function (Event $event) {
            return $event->getDate()->format('Ym') == (new DateTime())->format('Ym');
        }));
    }

    /**
     * @return EventCollection
     */
    public function filterWeek(): ?EventCollection
    {
        return new EventCollection($this->filter(function (Event $event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('next sunday midnight -1 sec'));
        }));
    }

    /**
     * @return EventCollection
     */
    public function filterDaily(): ?EventCollection
    {
        return new EventCollection($this->filter(function (Event $event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('tomorrow midnight -1 sec'));
        }));
    }

    /**
     * @return EventCollection
     */
    public function filterUpcoming(): ?EventCollection
    {
        return new EventCollection($this->filter(function ($event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('+4 hour'));
        }));
    }
}
