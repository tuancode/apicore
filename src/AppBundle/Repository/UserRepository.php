<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * UserRepository.
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * {@inheritdoc}
     */
    public function search(array $filters = [], PaginationInterface $pagination = null)
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

    /**
     * {@inheritdoc}
     */
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
