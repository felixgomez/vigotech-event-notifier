<?php

declare(strict_types=1);

namespace Vigotech;

use DateTimeImmutable;

final class Event
{
    private string $name;

    private DateTimeImmutable $date;

    private string $link;

    private Group $group;

    private string $type;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Event
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): Event
    {
        $this->date = $date;

        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): Event
    {
        $this->link = $link;

        return $this;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): Event
    {
        $this->group = $group;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }
}
