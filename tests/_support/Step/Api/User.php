<?php

namespace Step\Api;

/**
 * User step.
 */
class User extends \ApiTester
{
    /**
     * @return \AppBundle\Entity\User
     */
    public function createDummyUser()
    {
        $this->haveInRepository(
            \AppBundle\Entity\User::class,
            [
                'username' => 'dummy@test.net',
                'email' => 'dummy@test.net',
                'plainPassword' => '123456',
                'phone' => '+841200000001',
                'status' => \AppBundle\Entity\User::STATUS_ACTIVE,
                'enabled' => 1,
            ]
        );

        $user = new \AppBundle\Entity\User();
        $user->setEmail('dummy@test.net');
        $user->setPassword('123456');
        $user->setPhone('+841200000001');
        $user->setEnabled(true);
        $user->setStatus(\AppBundle\Entity\User::STATUS_ACTIVE);

        return $user;
    }

    public function loginAsUser()
    {
        
    }
}
