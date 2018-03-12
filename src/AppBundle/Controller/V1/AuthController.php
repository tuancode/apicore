<?php

namespace AppBundle\Controller\V1;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\User;
use AppBundle\Form\Type\RegisterType;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\UserBundle\Model\UserInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * AuthController.
 */
class AuthController extends AbstractController
{
    /**
     * API Login.
     *
     * @Operation(
     *     summary="API Login",
     *     consumes={"multipart/form-data"},
     *     tags={"/auth/v1"},
     *     @SWG\Response(
     *         response=200,
     *         description="API Login",
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
     * @param ParamFetcher $fetcher
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     * @throws \LogicException
     */
    public function loginAction(ParamFetcher $fetcher): array
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['email' => $fetcher->get('email')]);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $fetcher->get('password'));

        if (!$isValid) {
            throw new UnauthorizedHttpException('Invalid credentials');
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode(
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
     *     tags={"/auth/v1"},
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
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setEnabled(true);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $user;
        }

        return $form;
    }
}
