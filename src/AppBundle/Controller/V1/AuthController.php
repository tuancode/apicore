<?php

namespace AppBundle\Controller\V1;

use AppBundle\Controller\RestController;
use FOS\RestBundle\Controller\Annotations\Post;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * AuthController.
 */
class AuthController extends RestController
{
    /**
     * Login for authentication.
     *
     * @SWG\Parameter(
     *     name="username",
     *     description="Username",
     *     in="body",
     *     @SWG\Schema(
     *
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Login for authentication",
     * )
     * @SWG\Tag(name="/auth/v1")
     *
     * @Post("/login")
     *
     * @param Request $request
     *
     * @return array
     *
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function loginAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['username' => $request->get('username')]);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->get('username'));

        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode(
                [
                    'username' => $user->getUsername(),
                    'exp' => time() + 3600, // 1 hour expiration
                ]
            );

        return ['token' => $token];
    }
}
