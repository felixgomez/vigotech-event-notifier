<?php

declare(strict_types=1);

namespace Vigotech\Service;

use IntlDateFormatter;
use DateTimeInterface;

/**
 * Class DateFormatter
 * @package Vigotech\Service
 */
class DateFormatter
{

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $timezone;

    /**
     * DateFormatter constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->locale = $config['locale'];
        $this->format = $config['format'];
        $this->timezone = $config['timezone'];
    }

    /**
     * @param $date
     * @return string
     */
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

    /**
     * @param $date
     * @return string
     */
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
