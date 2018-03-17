<?php

namespace AppBundle\Pagination;

use FOS\RestBundle\Request\ParamFetcher;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Pagination.
 */
class Pagination
{
    public const FIRST_PAGE = 1;
    public const ITEMS_PER_PAGE = 30;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParams = [];

    /**
     * @var bool
     */
    private $pagination = true;

    /**
     * @var int
     */
    private $page = self::FIRST_PAGE;

    /**
     * @var int
     */
    private $itemsPerPage = self::ITEMS_PER_PAGE;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param mixed  $request
     * @param string $route
     * @param array  $routeParams
     *
     * @return Pagination
     *
     * @throws \InvalidArgumentException
     */
    public function parseRequest($request, string $route, array $routeParams = []): Pagination
    {
        if (!$request instanceof Request && !$request instanceof ParamFetcher) {
            throw new \InvalidArgumentException(
                sprintf('Expect request is instance of %s or %s', Request::class, ParamFetcher::class)
            );
        }

        $this->pagination = $request->get('pagination', true);
        $this->page = $request->get('page', self::FIRST_PAGE);
        $this->itemsPerPage = $request->get('limit', self::ITEMS_PER_PAGE);
        $this->route = $route;
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * Create a pagination collection.
     *
     * @param Pagerfanta $pager
     *
     * @return PaginatedCollection
     *
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Pagerfanta\Exception\LogicException
     */
    public function createCollection(Pagerfanta $pager): PaginatedCollection
    {
        $resources = [];
        foreach ($pager->getCurrentPageResults() as $result) {
            $resources[] = $result;
        }

        $createLinkUrl = function ($targetPage) {
            return $this->router->generate($this->route, array_merge($this->routeParams, ['page' => $targetPage]));
        };

        $paginatedCollection = new PaginatedCollection($resources, $pager->getNbResults());
        $paginatedCollection->addLink('self', $createLinkUrl($this->page));
        $paginatedCollection->addLink('first', $createLinkUrl(1));
        $paginatedCollection->addLink('last', $createLinkUrl($pager->getNbPages()));
        if ($pager->hasNextPage()) {
            $paginatedCollection->addLink('next', $createLinkUrl($pager->getNextPage()));
        }
        if ($pager->hasPreviousPage()) {
            $paginatedCollection->addLink('prev', $createLinkUrl($pager->getPreviousPage()));
        }

        return $paginatedCollection;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return bool
     */
    public function isPagination(): bool
    {
        return $this->pagination;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
