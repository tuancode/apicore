<?php

namespace AppBundle\Controller\V1;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

/**
 * AuthController.
 */
class AuthController extends FOSRestController
{
    /**
     * Login for authentication.
     *
     * @Route("/auth/v1/login", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Login for authentication",
     * )
     * @SWG\Parameter(name="username", type="string", in="formData", description="Username")
     * @SWG\Parameter(name="password", type="string", in="formData", description="Password")
     * @SWG\Tag(name="/auth/v1")
     *
     * @param Request $request
     */
    public function loginAction(Request $request)
    {
    }
}
