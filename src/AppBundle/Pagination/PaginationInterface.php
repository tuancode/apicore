<?php

namespace AppBundle\Pagination;

use Doctrine\ORM\QueryBuilder;

/**
 * PaginationInterface defines behavior structure of concrete pagination.
 */
interface PaginationInterface
{
    /**
     * Create a collection from query builder.
     *
     * @param QueryBuilder $builder
     * @param mixed        $request
     * @param array        $routeParams
     *
     * @return CollectionInterface|array
     */
    public function createCollection(QueryBuilder $builder, $request, array $routeParams = []);
}
