<?php

namespace Step\Api;

use AppBundle\Entity\User;

/**
 * User step.
 */
class UserStep extends \ApiTester
{
    public const ADMIN_EMAIL = 'admin@example.net';
    public const ADMIN_PASSWORD = 'admin';

    /**
     * @param string $email
     * @param string $password
     * @param string $phone
     */
    public function createUser(string $email, string $password, string $phone): void
    {
        $this->haveInRepository(
            User::class,
            [
                'username' => $email,
                'email' => $email,
                'plainPassword' => $password,
                'phone' => $phone,
                'status' => User::STATUS_ACTIVE,
                'enabled' => 1,
            ]
        );
    }

    /**
     * @return User
     */
    public function createDummyUser(): User
    {
        $this->createUser('dummy@test.net', '123456', '+841200000001');

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
        $this->sendPOST('/login.json', ['email' => self::ADMIN_EMAIL, 'password' => self::ADMIN_PASSWORD]);
        $token = $this->grabDataFromResponseByJsonPath('$.access_token');
        $this->amBearerAuthenticated($token[0]);
    }
}
