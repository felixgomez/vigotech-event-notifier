<?php

declare(strict_types=1);

namespace Vigotech\Service\GroupFetcher;

use GuzzleHttp\Client;
use Vigotech\Group;
use Vigotech\GroupCollection;

final class GroupFetcher extends Client
{
    private string $groupsUrl;

    public function __construct(string $groupsUrl)
    {
        parent::__construct([]);
        $this->groupsUrl = $groupsUrl;
    }

    public function getGroups(): GroupCollection
    {
        $response = $this->get($this->groupsUrl);

        $groups = json_decode($response->getBody()->getContents(), true);

        $groupCollection = new GroupCollection();

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
