<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Pagination\Pagination;
use Doctrine\ORM\EntityRepository;
use AppBundle\Pagination\PaginatedCollection;

/**
 * UserRepository.
 */
class UserRepository extends EntityRepository
{
    /**
     * Retrieves the collection of user with filter and pagination supported.
     *
     * @param array      $filters    Filters argument
     * @param Pagination $pagination Optional. Null is disable pagination
     *
     * @return User[]|PaginatedCollection Array of User is returned when no pagination,
     *                                    otherwise the PaginatedCollection is returned
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
            return $pagination->createCollection($builder);
        }

        return $builder->getQuery()->getResult();
    }
}
