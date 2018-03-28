<?php

namespace AppBundle\Pagination;

/**
 * Interface CollectionInterface.
 */
interface CollectionInterface
{
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
    public function addLink(string $ref, string $url);
}
