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
     * @var CollectionInterface
     */
    private $collection;

    /**
     * @var int
     */
    private $page = self::FIRST_PAGE;

    /**
     * @var int
     */
    private $itemsPerPage = self::ITEMS_PER_PAGE;

    /**
     * @param RouterInterface     $router
     * @param CollectionInterface $collection
     */
    public function __construct(RouterInterface $router, CollectionInterface $collection)
    {
        $this->router = $router;
        $this->collection = $collection;
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
    public function createCollection(QueryBuilder $builder, $request, array $routeParams = [])
    {
        if (!$request instanceof Request && !$request instanceof ParamFetcher) {
            throw new \InvalidArgumentException(
                sprintf('Expect request is instance of %s or %s', Request::class, ParamFetcher::class)
            );
        }

        if (null != $request->get('pagination')) {
            return $builder->getQuery()->getResult();
        }

        if (null != $request->get('page')) {
            $this->page = $request->get('page');
        }

        if (null != $request->get('itemsPerPage')) {
            $this->itemsPerPage = $request->get('itemsPerPage');
        }

        return $this->createPaginated($builder, $request->get('_route'));
    }

    /**
     * @param QueryBuilder $builder
     * @param string|null  $route
     * @param array        $routeParams
     *
     * @return CollectionInterface
     */
    private function createPaginated(QueryBuilder $builder, string $route, array $routeParams = []): CollectionInterface
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($builder, false));
        $pager->setMaxPerPage($this->itemsPerPage);
        $pager->setCurrentPage($this->page);

        $resources = [];
        foreach ($pager->getCurrentPageResults() as $result) {
            $resources[] = $result;
        }

        $createLinkUrl = function ($targetPage) use ($route, $routeParams) {
            return $this->router->generate($route, array_merge($routeParams, ['page' => $targetPage]));
        };

        $this->collection->setResource($resources, $pager->getNbResults());
        $this->collection->addLink('self', $createLinkUrl($this->page));
        $this->collection->addLink('first', $createLinkUrl(self::FIRST_PAGE));
        $this->collection->addLink('last', $createLinkUrl($pager->getNbPages()));
        if ($pager->hasNextPage()) {
            $this->collection->addLink('next', $createLinkUrl($pager->getNextPage()));
        }
        if ($pager->hasPreviousPage()) {
            $this->collection->addLink('prev', $createLinkUrl($pager->getPreviousPage()));
        }

        return $this->collection;
    }
}
