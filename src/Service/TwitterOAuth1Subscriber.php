<?php

declare(strict_types=1);

namespace Vigotech\Service;

use GuzzleHttp\Subscriber\Oauth\Oauth1;

final class TwitterOAuth1Subscriber extends Oauth1
{
    public function __construct(array $config)
    {
        parent::__construct($config['oauth']);
    }
}
