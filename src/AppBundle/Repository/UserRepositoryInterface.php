<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Pagination\PaginatedCollection;
use AppBundle\Pagination\PaginationInterface;

/**
 * UserRepository.
 */
interface UserRepositoryInterface
{
    /**
     * Retrieves the collection of user with filter and pagination supported.
     *
     * @param array               $filters    Filters argument
     * @param PaginationInterface $pagination Optional. Null is disable pagination
     *
     * @return User[]|PaginatedCollection Array of User is returned when no pagination,
     *                                    otherwise the PaginatedCollection is returned
     */
    public function search(array $filters = [], PaginationInterface $pagination = null);

    /**
     * Store user to database.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function save(User $user);
}
