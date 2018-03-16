<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * RestController.
 */
abstract class AbstractController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Extract query filters from request.
     *
     * @param mixed $request
     * @param array $additionFilters
     *
     * @return array|mixed
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
