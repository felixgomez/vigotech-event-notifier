<?php

declare(strict_types=1);

namespace Vigotech\Service;

use DateTimeInterface;
use IntlDateFormatter;

class DateFormatter
{
    private string $locale;

    private string $format;

    private string $timezone;

    public function __construct(array $config)
    {
        $this->locale   = $config['locale'];
        $this->format   = $config['format'];
        $this->timezone = $config['timezone'];
    }

    public function format(DateTimeInterface $date): string
    {
        $formatter = IntlDateFormatter::create(
            $this->locale,
            IntlDateFormatter::LONG,
            IntlDateFormatter::LONG,
            $this->timezone,
            IntlDateFormatter::GREGORIAN,
            $this->format
        );

        return $formatter->format($date);
    }

    public function getHour(DateTimeInterface $date): string
    {
        $formatter = IntlDateFormatter::create(
            $this->locale,
            IntlDateFormatter::LONG,
            IntlDateFormatter::LONG,
            $this->timezone,
            IntlDateFormatter::GREGORIAN,
            'HH:mm'
        );

        return $formatter->format($date);
    }
}
