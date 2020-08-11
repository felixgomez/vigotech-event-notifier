<?php

declare(strict_types=1);

namespace Vigotech;

use DateTime;

/**
 * Class Events
 * @package Vigotech
 */
final class Events extends Collection
{

    /**
     * @return Events
     */
    public function filterMonth(): ?Events
    {
        return new Events($this->filter(function (Event $event) {
            return ($event->getDate()->format('Ym') == (new DateTime())->format('Ym'));
        }));
    }

    /**
     * @return Events
     */
    public function filterWeek(): ?Events
    {
        return new Events($this->filter(function (Event $event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('next sunday midnight -1 sec'));
        }));
    }

    /**
     * @return Events
     */
    public function filterDaily(): ?Events
    {
        return new Events($this->filter(function (Event $event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('tomorrow midnight -1 sec'));
        }));
    }

    /**
     * @return Events
     */
    public function filterUpcoming()
    {
        return new Events($this->filter(function ($event) {
            return ($event->getDate() > new DateTime()) && ($event->getDate() <= (new DateTime())->modify('+4 hour'));
        }));
    }
}
