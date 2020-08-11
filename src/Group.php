<?php

declare(strict_types=1);

namespace Vigotech;

/**
 * Class Group.
 */
final class Group
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $logo;

    /**
     * @var array
     */
    private $links;

    /**
     * @var array
     */
    private $eventTypes;

    /**
     * @var array
     */
    private $videos;

    /**
     * Add new event.
     *
     * @return $this
     */
    public function addEvent(Event $event): Group
    {
        if (!in_array($event, $this->events)) {
            $this->events[] = $event;
        }

        return $this;
    }

    /**
     * Get group name.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set group name.
     *
     * @return $this
     */
    public function setName(?string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get logo.
     *
     * @return string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo(?string $logo): Group
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * get links.
     *
     * @return array
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * Set links.
     *
     * @return $this
     */
    public function setLinks(?array $links)
    {
        $this->links = !is_null($links) ? $links : [];

        return $this;
    }

    /**
     * Get event types.
     */
    public function getEventTypes(): array
    {
        return $this->eventTypes;
    }

    /**
     * Set event types.
     *
     * @param $eventTypes
     *
     * @return $this
     */
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

    /**
     * Get videos.
     *
     * @return array
     */
    public function getVideos(): ?array
    {
        return $this->videos;
    }

    /**
     * Set videos.
     *
     * @param $videos
     *
     * @return $this
     */
    public function setVideos(?array $videos): Group
    {
        $this->videos = !is_null($videos) ? $videos : [];

        return $this;
    }
}
