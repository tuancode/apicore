<?php

namespace AuthBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;

/**
 * AbstractController.
 */
abstract class AbstractController extends FOSRestController implements ClassResourceInterface
{
}
