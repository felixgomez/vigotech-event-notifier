<?php

declare(strict_types=1);

namespace Vigotech;

/**
 * Class Group
 * @package Vigotech
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
     * Add new event
     * @param Event $event
     * @return $this
     */
    public function addEvent(Event $event)
    {
        if (!in_array($event, $this->events)) {
            $this->events[] = $event;
        }

        return $this;
    }

    /**
     * Get group name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set group name
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get logo
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     * @return Group
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * get links
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set links
     * @param array $links
     * @return $this
     */
    public function setLinks(array $links)
    {
        $this->links = !is_null($links) ? $links : [];
        return $this;
    }

    /**
     * Get event types
     * @return array
     */
    public function getEventTypes()
    {
        return $this->eventTypes;
    }

    /**
     * Set event types
     * @param $eventTypes
     * @return $this
     */
    public function setEventTypes($eventTypes)
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
     * Get videos
     * @return array
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set videos
     * @param $videos
     * @return $this
     */
    public function setVideos($videos)
    {
        $this->videos = !is_null($videos) ? $videos : [];
        return $this;
    }
}
