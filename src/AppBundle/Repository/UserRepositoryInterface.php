<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\QueryBuilder;

/**
 * UserRepositoryInterface defines behavior structure of concrete user repository.
 */
interface UserRepositoryInterface
{
    /**
     * Retrieves the collection of user with filter and pagination supported.
     *
     * @param array $filter
     *
     * @return QueryBuilder
     */
    public function searchBuilder(array $filter = []): QueryBuilder;

    /**
     * Communicate with persistent layer to persist and update User to database.
     *
     * @param User $user
     */
    public function save(User $user): void;
}
