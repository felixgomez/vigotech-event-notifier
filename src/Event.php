<?php

declare(strict_types=1);

namespace Vigotech;

use DateTimeImmutable;

/**
 * Class Event
 * @package Vigotech
 */
final class Event
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var string
     */
    private $link;

    /**
     * @var Group
     */
    private $group;

    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Event
     */
    public function setName(string $name): Event
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return Event
     */
    public function setDate(DateTimeImmutable $date): Event
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return Event
     */
    public function setLink(string $link): Event
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $group
     * @return Event
     */
    public function setGroup(Group $group): Event
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Event
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
