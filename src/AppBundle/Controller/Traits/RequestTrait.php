<?php

namespace AppBundle\Controller\Traits;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait RequestTrait.
 */
trait RequestTrait
{
    /**
     * Extract query filters from request.
     *
     * @param mixed $request
     * @param array $additionFilters
     *
     * @return array|mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function getRequestFilters($request, array $additionFilters = [])
    {
        if (!$request instanceof Request && !$request instanceof ParamFetcher) {
            throw new \InvalidArgumentException(
                sprintf('Expect request is instance of %s or %s', Request::class, ParamFetcher::class)
            );
        }

        $filters = $request->get('filters', []);
        $filters = array_merge($filters, $additionFilters);

        return $filters;
    }
}
