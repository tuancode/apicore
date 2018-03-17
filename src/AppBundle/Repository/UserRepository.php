<?php

namespace AppBundle\Repository;

use AppBundle\Pagination\Pagination;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use AppBundle\Pagination\PaginatedCollection;

/**
 * UserRepository.
 */
class UserRepository extends EntityRepository
{
    /**
     * @param array      $filters
     * @param Pagination $pagination
     *
     * @return PaginatedCollection|array
     *
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Pagerfanta\Exception\LogicException
     * @throws \Pagerfanta\Exception\OutOfRangeCurrentPageException
     * @throws \Pagerfanta\Exception\NotIntegerMaxPerPageException
     * @throws \Pagerfanta\Exception\NotIntegerCurrentPageException
     * @throws \Pagerfanta\Exception\LessThan1MaxPerPageException
     * @throws \Pagerfanta\Exception\LessThan1CurrentPageException
     */
    public function search(array $filters = [], Pagination $pagination = null)
    {
        $builder = $this->createQueryBuilder('u');

        if (!empty($filters['email'])) {
            $builder->andWhere('u.email = :email')->setParameter('email', $filters['email']);
        }

        if (!empty($filters['phone'])) {
            $builder->andWhere('u.phone = :phone')->setParameter('phone', $filters['phone']);
        }

        if (!empty($filters['status'])) {
            $builder->andWhere('u.status = :status')->setParameter('status', $filters['status']);
        }

        if ($pagination && $pagination->isPagination()) {
            $pagerfanta = new Pagerfanta(new DoctrineORMAdapter($builder));
            $pagerfanta->setMaxPerPage($pagination->getItemsPerPage());
            $pagerfanta->setCurrentPage($pagination->getPage());

            return $pagination->createCollection($pagerfanta);
        }

        return $builder->getQuery()->getResult();
    }
}
