<?php

namespace AppBundle\Controller\V1;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserCreateType;
use AppBundle\Form\Type\UserType;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * UserController.
 */
class UserController extends AbstractController
{
    /**
     * Get list of user.
     *
     * @Operation(
     *     summary="Get list of user",
     *     tags={"/api/v1/user"},
     *     @SWG\Response(
     *         response=200,
     *         description="Response successful",
     *         @SWG\Schema(
     *             type="array",
     *             @Model(type=User::class)
     *         )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found"
     *     )
     * )
     *
     * @View(
     *     serializerGroups={"userList"}
     * )
     *
     * @Get("/user")
     *
     * @return User[]
     *
     * @throws \LogicException
     */
    public function cgetAction(): array
    {
        return $this->getDoctrine()->getRepository(User::class)->findAll();
    }

    /**
     * Get an user information.
     *
     * @Operation(
     *     summary="Get an user information",
     *     tags={"/api/v1/user"},
     *     @SWG\Response(
     *         response=200,
     *         description="Response successful",
     *         @Model(type=User::class)
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found"
     *     ),
     * )
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
     * @Operation(
     *     summary="Create a new user",
     *     tags={"/api/v1/user"},
     *     @SWG\Parameter(
     *         name="user",
     *         in="body",
     *         description="Parameters",
     *         @Model(type=UserCreateType::class)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Response successful",
     *         @Model(type=User::class)
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Request data error"
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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
     *
     * @throws \LogicException
     */
    public function postAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserCreateType::class, $user);
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
     * @Operation(
     *     summary="Update an exist user",
     *     tags={"/api/v1/user"},
     *     @SWG\Parameter(
     *         name="user",
     *         in="body",
     *         description="Parameters",
     *         @Model(type=UserType::class)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Response successful",
     *         @Model(type=User::class)
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Request data error"
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found"
     *     )
     * )
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
     *
     * @throws \LogicException
     */
    public function putAction(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user, ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $user;
        }

        return $form;
    }

    /**
     * Delete an exist user.
     *
     * @Operation(
     *     summary="Delete an exist user",
     *     tags={"/api/v1/user"},
     *     @SWG\Response(
     *         response=200,
     *         description="Response successful",
     *         @Model(type=User::class)
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Request data error"
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found"
     *     )
     * )
     *
     * @View(
     *     serializerGroups={"userDetail"}
     * )
     *
     * @Delete("/user/{id}")
     *
     * @param User $user
     *
     * @return User
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \LogicException
     */
    public function deleteAction(User $user): User
    {
        if (User::STATUS_REMOVED === $user->getStatus()) {
            throw new BadRequestHttpException('User is removed already');
        }

        $user->setStatus(User::STATUS_REMOVED);
        $this->getDoctrine()->getManager()->flush();

        return $user;
    }
}
