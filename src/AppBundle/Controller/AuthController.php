<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\RegisterType;
use AppBundle\Repository\UserRepositoryInterface;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\UserBundle\Model\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * AuthController.
 */
class AuthController extends FOSRestController implements ClassResourceInterface
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
     * API Login.
     *
     * @Operation(
     *     summary="Login",
     *     consumes={"multipart/form-data"},
     *     tags={"/api/v1/auth"},
     *     @SWG\Response(
     *         response=200,
     *         description="Login",
     *         examples={
     *             "application/json":{
     *                 "access_token": "string",
     *             },
     *         }
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Not found"
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     *
     * @View()
     *
     * @RequestParam(name="email", description="Email", nullable=false)
     * @RequestParam(name="password", description="Password", nullable=false)
     *
     * @Post("/login")
     *
     * @param ParamFetcher                 $fetcher
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param JWTEncoderInterface          $jwtEncoder
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function loginAction(
        ParamFetcher $fetcher,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTEncoderInterface $jwtEncoder
    ): array {
        $user = $this->userRepository->findOneBy(['email' => $fetcher->get('email')]);
        if (!$user instanceof User) {
            throw $this->createNotFoundException();
        }

        $isValid = $passwordEncoder->isPasswordValid($user, $fetcher->get('password'));
        if (!$isValid) {
            throw new UnauthorizedHttpException('Invalid credentials');
        }

        $token = $jwtEncoder->encode(
            [
                'username' => $user->getEmail(),
                'exp' => time() + 3600, // 1 hour expiration
            ]
        );

        return ['access_token' => $token];
    }

    /**
     * Registration.
     *
     * @Operation(
     *     summary="Registration",
     *     tags={"/api/v1/auth"},
     *     @SWG\Parameter(
     *         name="param",
     *         in="body",
     *         @Model(type=RegisterType::class)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Response successful",
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Request data error"
     *     ),
     * )
     *
     * @View(
     *     serializerGroups={"userDetail"}
     * )
     *
     * @Post("/register")
     *
     * @param Request $request
     *
     * @return UserInterface|FormInterface
     *
     * @throws \LogicException
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEnabled(true);
            $this->userRepository->save($user);

            return $user;
        }

        return $form;
    }
}
