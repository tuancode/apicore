<?php

namespace AppBundle\Controller\V1;

use AppBundle\Controller\RestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Swagger\Annotations as SWG;

/**
 * AuthController.
 */
class AuthController extends RestController
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
     * @Post("/login")
     *
     * @param ParamFetcher $fetcher
     */
    public function loginAction(ParamFetcher $fetcher)
    {
    }
}
