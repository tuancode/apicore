<?php

namespace AppBundle\Controller\V1;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Swagger\Annotations as SWG;

/**
 * AuthController.
 */
class AuthController extends FOSRestController
{
    /**
     * Login for authentication.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Login for authentication",
     * )
     * @SWG\Tag(name="/auth/v1")
     *
     * @RequestParam(name="username", description="Username")
     * @RequestParam(name="password", description="Password")
     *
     * @Post("/login", name="app.user.cget")
     *
     * @param ParamFetcher $fetcher
     */
    public function loginAction(ParamFetcher $fetcher)
    {
    }
}
