<?php

namespace AppBundle\Pagination;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * PaginatedCollection.
 */
class PaginatedCollection
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $count;

    /**
     * @var ArrayCollection
     */
    private $_links;

    /**
     * @param array $items
     * @param int   $totalItems
     */
    public function __construct(array $items, int $totalItems)
    {
        $this->items = $items;
        $this->total = $totalItems;
        $this->count = \count($items);
        $this->_links = new ArrayCollection();
    }

    /**
     * @param string $ref
     * @param string $url
     */
    public function addLink(string $ref, string $url): void
    {
        $this->_links = new ArrayCollection([$ref => $url]);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return ArrayCollection
     */
    public function getLinks(): ArrayCollection
    {
        return $this->_links;
    }
}
