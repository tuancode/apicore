<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Pagination\PaginationFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * UserRepository.
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * {@inheritdoc}
     */
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param array             $filters
     * @param PaginationFactory $pagination
     *
     * @return array
     */
    public function search(array $filters = [], PaginationFactory $pagination = null): array
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

        $pagination->createCollection($builder);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('user');
    }
}
