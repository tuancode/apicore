<?php

namespace Step\Api;

use AppBundle\Entity\User;
use Helper\ServiceHelper;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

/**
 * User step.
 */
class UserStep extends \ApiTester
{
    public const ADMIN_EMAIL = 'admin@example.net';
    public const ADMIN_PASSWORD = 'admin';
    public const ADMIN_PHONE = '+841208667413';

    /**
     * @var JWTEncoderInterface
     */
    protected $jwtEncoder;

    /**
     * @param ServiceHelper $service
     *
     * @throws \Codeception\Exception\ModuleException
     */
    protected function _inject(ServiceHelper $service): void
    {
        $this->jwtEncoder = $service->getJwtEncoder();
    }

    /**
     * @param string $email
     * @param string $phone
     * @param string $status
     *
     * @return int
     */
    public function createUser(string $email, string $phone, string $status): int
    {
        return $this->haveInRepository(
            User::class,
            [
                'username' => $email,
                'email' => $email,
                'plainPassword' => 123456,
                'phone' => $phone,
                'status' => $status,
                'enabled' => 1,
            ]
        );
    }

    /**
     * @return User
     */
    public function createDummyUser(): User
    {
        $this->createUser('dummy@test.net', '+841200000001', User::STATUS_ACTIVE);

        $user = new User();
        $user->setEmail('dummy@test.net');
        $user->setPassword('123456');
        $user->setPhone('+841200000001');
        $user->setEnabled(true);
        $user->setStatus(User::STATUS_ACTIVE);

        return $user;
    }

    /**
     * @throws \Exception
     */
    public function login(): void
    {
        $token = $this->jwtEncoder->encode(
            [
                'username' => self::ADMIN_EMAIL,
                'exp' => time() + 3600, // 1 hour expiration
            ]
        );
        $this->amBearerAuthenticated($token);
    }
}
