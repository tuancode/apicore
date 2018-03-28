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
class Pagination implements PaginationInterface
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
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Pagerfanta\Exception\LogicException
     * @throws \Pagerfanta\Exception\OutOfRangeCurrentPageException
     * @throws \Pagerfanta\Exception\NotIntegerCurrentPageException
     * @throws \Pagerfanta\Exception\LessThan1CurrentPageException
     * @throws \Pagerfanta\Exception\NotIntegerMaxPerPageException
     * @throws \Pagerfanta\Exception\LessThan1MaxPerPageException
     */
    public function createCollection(QueryBuilder $builder): PaginatedCollection
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($builder, false));
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
        $paginatedCollection->addLink('first', $createLinkUrl(self::FIRST_PAGE));
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
     * {@inheritdoc}
     */
    public function isPagination(): bool
    {
        return $this->pagination;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function setRequest($request): self
    {
        if (!$request instanceof Request && !$request instanceof ParamFetcher) {
            throw new \InvalidArgumentException(
                sprintf('Expect request is instance of %s or %s', Request::class, ParamFetcher::class)
            );
        }

        if (null != $request->get('pagination')) {
            $this->pagination = $request->get('pagination');
        }

        if (null != $request->get('page')) {
            $this->page = $request->get('page');
        }

        if (null != $request->get('itemsPerPage')) {
            $this->itemsPerPage = $request->get('itemsPerPage');
        }

        $this->route = $request->get('_route');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRouteParams(array $routeParams = []): self
    {
        $this->routeParams = $routeParams;

        return $this;
    }
}
