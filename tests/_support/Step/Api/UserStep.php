<?php

namespace Step\Api;

use AppBundle\Entity\User;
use Helper\Api;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

/**
 * User step.
 */
class UserStep extends \ApiTester
{
    public const ADMIN_EMAIL = 'admin@example.net';
    public const ADMIN_PASSWORD = 'admin';

    /**
     * @var JWTEncoderInterface
     */
    protected $jwtEncoder;

    /**
     * @param Api $api
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Codeception\Exception\ModuleException
     */
    protected function _inject(Api $api): void
    {
        /* @noinspection MissingService */
        $this->jwtEncoder = $api->getContainer()->get('lexik_jwt_authentication.encoder');
    }

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
        $token = $this->jwtEncoder->encode(
            [
                'username' => self::ADMIN_EMAIL,
                'exp' => time() + 3600, // 1 hour expiration
            ]
        );
        $this->amBearerAuthenticated($token);
    }
}
