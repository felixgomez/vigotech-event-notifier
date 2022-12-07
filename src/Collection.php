<?php

declare(strict_types=1);

namespace Vigotech;

use ArrayIterator;
use Countable;
use IteratorAggregate;

use function Lambdish\Phunctional\filter;

/**
 * Class Collection.
 */
abstract class Collection implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    private $items;

    /**
     * Collection constructor.
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getItems());
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param $item
     */
    public function add($item): void
    {
        if (!in_array($item, $this->items)) {
            $this->items[] = $item;
        }
    }

    protected function filter(callable $fn): array
    {
        return filter($fn, $this->items);
    }
}
