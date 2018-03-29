<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
    public function searchBuilder(array $filter = []): QueryBuilder
    {
        $builder = $this->createQueryBuilder('u');

        if (!empty($filter['email'])) {
            $builder->andWhere('u.email = :email')->setParameter('email', $filter['email']);
        }

        if (!empty($filter['phone'])) {
            $builder->andWhere('u.phone = :phone')->setParameter('phone', $filter['phone']);
        }

        if (!empty($filter['status'])) {
            $builder->andWhere('u.status = :status')->setParameter('status', $filter['status']);
        }

        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $user): void
    {
        $user->setStatus(User::STATUS_REMOVED);
        $this->_em->flush();
    }
}
