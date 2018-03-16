<?php

namespace AppBundle\Pagination;

use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Request\ParamFetcher;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * PaginationFactory.
 */
class PaginationFactory
{
    public const PAGE = 1;
    public const ITEMS_PER_PAGE = 30;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Create a pagination collection.
     *
     * @param QueryBuilder $builder
     * @param mixed        $request
     * @param string       $route
     * @param array        $routeParams
     *
     * @return PaginatedCollection
     */
    public function createCollection(QueryBuilder $builder, $request, string $route, array $routeParams = [])
    {
        if (!$request instanceof Request && !$request instanceof ParamFetcher) {
            throw new \InvalidArgumentException(
                sprintf('Expect request is instance of %s or %s', Request::class, ParamFetcher::class)
            );
        }

        $page = $request->get('page', self::PAGE);

        $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($builder));
        $pagerfanta->setMaxPerPage(self::ITEMS_PER_PAGE);
        $pagerfanta->setCurrentPage($page);

        $programmers = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $programmers[] = $result;
        }

        $paginatedCollection = new PaginatedCollection($programmers, $pagerfanta->getNbResults());
        $createLinkUrl = function ($targetPage) use ($route, $routeParams) {
            return $this->router->generate($route, array_merge($routeParams, ['page' => $targetPage]));
        };

        // Create link
        $paginatedCollection->addLink('self', $createLinkUrl($page));

        $paginatedCollection->addLink('first', $createLinkUrl(1));

        $paginatedCollection->addLink('last', $createLinkUrl($pagerfanta->getNbPages()));

        if ($pagerfanta->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pagerfanta->getNextPage()));
        }

        if ($pagerfanta->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pagerfanta->getPreviousPage()));
        }

        return $paginatedCollection;
    }
}
