<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

trait EventNotifierPreviewTrait
{
    private function preview(string $payload)
    {
        print $this->type() . PHP_EOL;
        print str_repeat('-', strlen($this->type())) . PHP_EOL;
        print $payload . PHP_EOL . PHP_EOL;
    }
}
