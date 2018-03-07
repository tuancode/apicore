<?php

namespace AppBundle\Controller\V1;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends FOSRestController
{
    /**
     * Get the specified user information.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/v1/{user}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the information of an user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"full"})
     *     )
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="string",
     *     description="The field used to order rewards"
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @param User $user
     */
    public function getAction(User $user)
    {
        // ...
    }
}
