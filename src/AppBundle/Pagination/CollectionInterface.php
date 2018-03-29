<?php

namespace AppBundle\Pagination;

/**
 * CollectionInterface defines behavior structure of concrete collection.
 */
interface CollectionInterface
{
    /**
     * @param array $items
     * @param int   $totalItems
     */
    public function setResource(array $items, int $totalItems): void;

    /**
     * @return array
     */
    public function getItems(): array;

    /**
     * @return int
     */
    public function getTotal(): int;

    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @return array
     */
    public function getLinks(): array;

    /**
     * @param string $ref
     * @param string $url
     */
    public function addLink(string $ref, string $url): void;
}
