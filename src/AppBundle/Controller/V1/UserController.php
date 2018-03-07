<?php

namespace AppBundle\Controller\V1;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;

/**
 * UserController.
 */
class UserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get user list.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get user list",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"full"})
     *     )
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @Get("/user")
     */
    public function cgetAction()
    {
        return 'Hello world!';
    }

    /**
     * Get the specified user information.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the information of an user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"full"})
     *     )
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @Get("/user/{user}")
     *
     * @param User $user
     */
    public function getAction(User $user)
    {
        // ...
    }

    /**
     * Create a new user.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Create a new user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"full"})
     *     )
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @Post("/user")
     *
     * @param Request $request
     */
    public function postAction(Request $request)
    {
        // ...
    }

    /**
     * Update an exist user.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Update an exist user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"full"})
     *     )
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @Put("/user/{user}")
     *
     * @param User    $user
     * @param Request $request
     */
    public function putAction(User $user, Request $request)
    {
        // ...
    }

    /**
     * Delete an exist user.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete an exist user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class, groups={"full"})
     *     )
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @Delete("/user/{user}")
     *
     * @param User    $user
     * @param Request $request
     */
    public function deleteAction(User $user, Request $request)
    {
        // ...
    }
}
