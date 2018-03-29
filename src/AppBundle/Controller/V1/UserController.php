<?php

namespace AppBundle\Controller\V1;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserCreateType;
use AppBundle\Form\Type\UserType;
use AppBundle\Pagination\CollectionInterface;
use AppBundle\Pagination\PaginationInterface;
use AppBundle\Repository\UserRepositoryInterface;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\QueryParam;
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
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieves the collection of User resources.
     *
     * @Operation(
     *     summary="Retrieves the collection of User resources.",
     *     tags={"/api/v1/user"},
     *     @SWG\Response(
     *         response=200,
     *         description="Response successful",
     *         @SWG\Schema(type="array", @SWG\Items(ref=@Model(type=User::class)))
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
     *     serializerGroups={"paginated", "userList"}
     * )
     *
     * @QueryParam(name="filters[email]", description="Filter by email")
     * @QueryParam(name="filters[phone]", description="Filter by phone")
     * @QueryParam(name="filters[status]", description="Filter by status")
     * @QueryParam(name="pagination", description="0:disable 1:enable",
     *     nullable=true, allowBlank=true, strict=false, requirements="[1|0]")
     * @QueryParam(name="page", description="Page of collection", nullable=true, allowBlank=true, strict=false)
     * @QueryParam(name="itemsPerPage", description="Items per page", nullable=true, allowBlank=true, strict=false)
     *
     * @Get("/user")
     *
     * @param Request             $request
     * @param PaginationInterface $pagination
     *
     * @return CollectionInterface|User[]
     *
     * @throws \InvalidArgumentException
     */
    public function cgetAction(Request $request, PaginationInterface $pagination)
    {
        $filters = $this->getRequestFilters($request);
        $builder = $this->userRepository->searchBuilder($filters);

        return $pagination->createCollection($builder, $request);
    }

    /**
     * Retrieves an User resource.
     *
     * @Operation(
     *     summary="Retrieves an User resource.",
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
     * @return User
     */
    public function getAction(User $user): User
    {
        return $user;
    }

    /**
     * Create an User resource.
     *
     * @Operation(
     *     summary="Create an User resource.",
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
            $this->userRepository->save($user);

            return $user;
        }

        return $form;
    }

    /**
     * Replace the User resource.
     *
     * @Operation(
     *     summary="Replace the User resource.",
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
            $this->userRepository->save($user);

            return $user;
        }

        return $form;
    }

    /**
     * Removes the User resource.
     *
     * @Operation(
     *     summary="Removes the User resource.",
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
     * @View()
     *
     * @Delete("/user/{id}")
     *
     * @param User $user
     *
     * @return bool
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \LogicException
     */
    public function deleteAction(User $user): bool
    {
        if (User::STATUS_REMOVED === $user->getStatus()) {
            throw new BadRequestHttpException('User is removed already');
        }

        $this->userRepository->remove($user);

        return true;
    }
}
