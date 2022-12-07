<?php

declare(strict_types=1);

namespace Vigotech;

final class Group
{
    private ?string $name;

    private ?string $logo;

    private ?array $links;

    private ?array $eventTypes;

    private ?array $videos;

    public function addEvent(Event $event): Group
    {
        if (!in_array($event, $this->events)) {
            $this->events[] = $event;
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): Group
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function setLinks(?array $links)
    {
        $this->links = !is_null($links) ? $links : [];

        return $this;
    }

    public function getEventTypes(): ?array
    {
        return $this->eventTypes;
    }

    public function setEventTypes(?array $eventTypes): Group
    {
        if (is_null($eventTypes)) {
            $this->eventTypes = [];

            return $this;
        }

        if (isset($eventTypes['type'])) {
            $this->eventTypes[] = $eventTypes;

            return $this;
        }

        $this->eventTypes = $eventTypes;

        return $this;
    }

    public function getVideos(): ?array
    {
        return $this->videos;
    }

    public function setVideos(?array $videos): Group
    {
        $this->videos = !is_null($videos) ? $videos : [];

        return $this;
    }
}
