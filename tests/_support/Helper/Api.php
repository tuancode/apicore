<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
use Codeception\Module;

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
    public function getContainer()
    {
        // accessing container
        return $this->getModule('Symfony')->container;
    }
}
