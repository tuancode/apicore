<?php

namespace AppBundle\Pagination;

use Doctrine\ORM\QueryBuilder;

/**
 * PaginationInterface.
 */
interface PaginationInterface
{
    /**
     * Create a pagination collection.
     *
     * @param QueryBuilder $builder
     *
     * @return PaginatedCollection
     */
    public function createCollection(QueryBuilder $builder): PaginatedCollection;

    /**
     * Whether pagination is enable or not.
     *
     * @return bool
     */
    public function isPagination(): bool;

    /**
     * Set up pagination information from request.
     *
     * @param mixed $request
     */
    public function setRequest($request);

    /**
     * Set up route params.
     *
     * @param array $routeParams
     */
    public function setRouteParams(array $routeParams = []);
}
