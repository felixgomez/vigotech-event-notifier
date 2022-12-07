<?php

declare(strict_types=1);

namespace Vigotech;

use DateTime;

final class EventCollection extends Collection
{
    public function filterMonth(): ?EventCollection
    {
        return new EventCollection($this->filter(function (Event $event) {
            return $event->getDate()->format('Ym') == (new DateTime())->format('Ym');
        }));
    }

    public function filterWeek(): ?EventCollection
    {
        return new EventCollection($this->filter(function (Event $event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('next sunday midnight -1 sec'));
        }));
    }

    public function filterDaily(): ?EventCollection
    {
        return new EventCollection($this->filter(function (Event $event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('tomorrow midnight -1 sec'));
        }));
    }

    public function filterUpcoming(): ?EventCollection
    {
        return new EventCollection($this->filter(function ($event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('+4 hour'));
        }));
    }
}
