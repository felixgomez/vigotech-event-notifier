<?php

declare(strict_types=1);

namespace Vigotech\Service;

use GuzzleHttp\Subscriber\Oauth\Oauth1;

/**
 * Class TwitterOAuth1Subscriber.
 */
final class TwitterOAuth1Subscriber extends Oauth1
{
    /**
     * TwitterOAuth1Subscriber constructor.
     */
    public function __construct(array $config)
    {
        parent::__construct($config['oauth']);
    }
}
