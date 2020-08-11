<?php

declare(strict_types=1);

namespace Vigotech;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use function Lambdish\Phunctional\filter;

/**
 * Class Collection
 * @package Vigotech
 */
abstract class Collection implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    private $items;

    /**
     * Collection constructor.
     * @param array $items
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

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return array
     */
    public function getItems()
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

    /**
     * @param callable $fn
     * @return array
     */
    protected function filter(callable $fn): array
    {
        return filter($fn, $this->items);
    }
}
