<?php

namespace AppBundle\Pagination;

/**
 * PaginatedCollection.
 */
class PaginatedCollection implements CollectionInterface
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
     * @var array
     */
    private $links;

    /**
     * @param array $items
     * @param int   $totalItems
     */
    public function setResource(array $items, int $totalItems): void
    {
        $this->items = $items;
        $this->total = $totalItems;
        $this->count = \count($items);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * {@inheritdoc}
     */
    public function addLink(string $ref, string $url): void
    {
        $this->links[$ref] = $url;
    }
}
