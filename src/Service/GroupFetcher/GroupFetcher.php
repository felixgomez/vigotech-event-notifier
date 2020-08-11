<?php

declare(strict_types=1);

namespace Vigotech\Service\GroupFetcher;

use GuzzleHttp\Client;
use Vigotech\Group;
use Vigotech\Groups;

/**
 * Class GroupFetcher.
 */
final class GroupFetcher extends Client
{
    /**
     * @var string
     */
    private $groupsUrl;

    /**
     * GroupFetcher constructor.
     *
     * @param $groupsUrl
     */
    public function __construct($groupsUrl)
    {
        parent::__construct([]);
        $this->groupsUrl = $groupsUrl;
    }

    public function getGroups(): Groups
    {
        $response = $this->get($this->groupsUrl);

        $groups = json_decode($response->getBody()->getContents(), true);

        $groupCollection = new Groups();

        $group = new Group();
        $group
            ->setName($groups['name'] ?? '')
            ->setLogo($groups['logo'] ?? '')
            ->setLinks($groups['links'] ?? '')
            ->setEventTypes($groups['events'] ?? null)
            ->setVideos($groups['videos'] ?? null);

        $groupCollection->add($group);

        foreach ($groups['members'] as $item) {
            $group = new Group();
            $group
                ->setName($item['name'])
                ->setLogo($item['logo'])
                ->setLinks($item['links'])
                ->setEventTypes($item['events'] ?? null)
                ->setVideos($item['videos'] ?? null);

            $groupCollection->add($group);
        }

        return $groupCollection;
    }
}
