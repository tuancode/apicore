<?php

namespace Step\Api;

use AppBundle\Entity\User;

/**
 * User step.
 */
class UserStep extends \ApiTester
{
    /**
     * @param string $email
     * @param string $password
     * @param string $phone
     */
    public function createUser(string $email, string $password, string $phone)
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
    public function createDummyUser()
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
     * @param User $user
     *
     * @throws \Exception
     */
    public function loginAs(User $user)
    {
        $this->sendPOST('/login.json', ['email' => $user->getEmail(), 'password' => $user->getPassword()]);
        $token = $this->grabDataFromResponseByJsonPath('$.access_token');
        $this->amBearerAuthenticated($token[0]);
    }
}
