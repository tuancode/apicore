<?php

namespace AppBundle\Controller\V1;

use AppBundle\Controller\RestController;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UserType;

/**
 * UserController.
 */
class UserController extends RestController
{
    /**
     * Get list of user.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get list of user",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class)
     *     )
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @View(
     *     serializerGroups={"userList"}
     * )
     *
     * @Get("/user")
     *
     * @return User[]
     */
    public function cgetAction(): array
    {
        return $this->getDoctrine()->getRepository(User::class)->findAll();
    }

    /**
     * Get user information.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get user information",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=User::class)
     *     )
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @View(
     *     serializerGroups={"userDetail"}
     * )
     *
     * @Get("/user/{id}")
     *
     * @param User $user
     *
     * @return User;
     */
    public function getAction(User $user): User
    {
        return $user;
    }

    /**
     * Create a new user.
     *
     * @Security(name="abc")
     * @SWG\Parameter(
     *     name="user",
     *     in="body",
     *     description="User creation parameters",
     *     type="object",
     *     parameter="user",
     *     @Model(type=UserType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Create a new user",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @View(
     *     serializerGroups={"userDetail"}
     * )
     *
     * @Post("/user")
     *
     * @param Request $request
     *
     * @return User|FormInterface
     */
    public function postAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $user;
        }

        return $form;
    }

    /**
     * Update an exist user.
     *
     * @SWG\Parameter(
     *     name="user",
     *     in="body",
     *     description="User update parameters",
     *     type="object",
     *     parameter="user",
     *     @Model(type=UserType::class)
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Update an exist user",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @View(
     *     serializerGroups={"userDetail"}
     * )
     *
     * @Put("/user/{id}")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return FormInterface|User
     */
    public function putAction(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $user;
        }

        return $form;
    }

    /**
     * Delete an exist user.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Delete an exist user",
     * )
     * @SWG\Tag(name="/api/v1/user")
     *
     * @Delete("/user/{id}")
     *
     * @param User    $user
     * @param Request $request
     */
    public function deleteAction(User $user, Request $request)
    {
        // ...
    }
}
