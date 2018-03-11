<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;

/**
 * RestController.
 */
abstract class RestController extends FOSRestController implements ClassResourceInterface
{
}
