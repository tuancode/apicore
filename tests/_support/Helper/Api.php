<?php

namespace Helper;

use Codeception\Module;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Api.
 */
class Api extends Module
{
    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function getContainer(): ContainerInterface
    {
        /* @noinspection PhpUndefinedFieldInspection */
        return $this->getModule('Symfony')->kernel->getContainer();
    }
}
