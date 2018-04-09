<?php

namespace Helper;

use Codeception\Module;
use Codeception\Module\Symfony;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

/**
 * Class Api.
 */
class ServiceHelper extends Module
{
    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function getJwtEncoder(): JWTEncoderInterface
    {
        /** @var Symfony $symfonyModule */
        $symfonyModule = $this->getModule('Symfony');

        return $symfonyModule->grabService('lexik_jwt_authentication.encoder');
    }
}
