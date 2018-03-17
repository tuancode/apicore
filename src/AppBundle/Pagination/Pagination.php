<?php

namespace AppBundle\Pagination;

use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Request\ParamFetcher;
use Pagerfanta\Adapter\DoctrineORMAdapter;
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
     * Create a pagination collection.
     *
     * @param QueryBuilder $builder
     *
     * @return PaginatedCollection
     *
     * @throws \Pagerfanta\Exception\LogicException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Pagerfanta\Exception\OutOfRangeCurrentPageException
     * @throws \Pagerfanta\Exception\NotIntegerCurrentPageException
     * @throws \Pagerfanta\Exception\NotIntegerMaxPerPageException
     * @throws \Pagerfanta\Exception\LessThan1CurrentPageException
     * @throws \Pagerfanta\Exception\LessThan1MaxPerPageException
     */
    public function createCollection(QueryBuilder $builder): PaginatedCollection
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($builder));
        $pager->setMaxPerPage($this->itemsPerPage);
        $pager->setCurrentPage($this->page);

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
     * Set up pagination information from request.
     *
     * @param mixed $request
     *
     * @return Pagination
     *
     * @throws \InvalidArgumentException
     */
    public function setRequest($request): Pagination
    {
        if (!$request instanceof Request && !$request instanceof ParamFetcher) {
            throw new \InvalidArgumentException(
                sprintf('Expect request is instance of %s or %s', Request::class, ParamFetcher::class)
            );
        }

        $this->pagination = $request->get('pagination', true);
        $this->page = $request->get('page', self::FIRST_PAGE);
        $this->itemsPerPage = $request->get('limit', self::ITEMS_PER_PAGE);
        $this->route = $request->get('_route');

        return $this;
    }

    /**
     * Set up route params.
     *
     * @param array $routeParams
     *
     * @return Pagination
     */
    public function setRouteParams(array $routeParams = []): Pagination
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * Whether pagination is enable or not.
     *
     * @return bool
     */
    public function isPagination(): bool
    {
        return $this->pagination;
    }
}
