<?php

declare(strict_types=1);

namespace Vigotech\Service\EventNotifier;

trait EventNotifierPreviewTrait
{
    private function preview(string $payload)
    {
        echo $this->type().PHP_EOL;
        echo str_repeat('-', strlen($this->type())).PHP_EOL;
        echo $payload.PHP_EOL.PHP_EOL;
    }
}
