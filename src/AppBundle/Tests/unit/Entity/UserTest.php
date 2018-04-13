<?php

namespace AppBundle\Entity;

use Codeception\Module\Symfony;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * UserTest.
 */
class UserTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    /**
     * @var \AppBundle\UnitTester
     */
    protected $tester;

    public function testGetId(): void
    {
        $user = new User();
        $this->assertNull($user->getId());

        $user->setId(1);
        $this->assertEquals(1, $user->getId());
    }

    public function testGetUsernameAndEmail(): void
    {
        $user = new User();
        $this->assertNull($user->getUsername());
        $this->assertNull($user->getEmail());

        $user->setEmail('test@test.net');
        $this->assertEquals('test@test.net', $user->getEmail());
        $this->assertEquals('test@test.net', $user->getUsername());
    }

    public function testGetPassword(): void
    {
        $user = new User();
        $this->assertNull($user->getPassword());

        $user->setPassword('123456');
        $this->assertEquals('123456', $user->getPassword());
    }

    public function testGetStatus(): void
    {
        $user = new User();
        $this->assertEquals($user->getStatus(), User::STATUS_ACTIVE);

        $user->setStatus(User::STATUS_SUSPENDED);
        $this->assertEquals(User::STATUS_SUSPENDED, $user->getStatus());
    }

    public function testIsEnabled(): void
    {
        $user = new User();
        $this->assertEquals($user->isEnabled(), User::DISABLED);

        $user->setEnabled(User::ENABLED);
        $this->assertEquals(User::ENABLED, $user->isEnabled());
    }

    public function testGetRoles(): void
    {
        $user = new User();
        $this->assertEquals([User::ROLE_DEFAULT], $user->getRoles());

        $user->setRoles([User::ROLE_SUPER_ADMIN]);
        $this->assertEquals([User::ROLE_SUPER_ADMIN, User::ROLE_DEFAULT], $user->getRoles());
    }

    public function testGetPhone(): void
    {
        $user = new User();
        $this->assertNull($user->getPhone());

        $user->setPhone('+841208772134');
        $this->assertEquals('+841208772134', $user->getPhone());
    }

    public function testGetCreatedDate(): void
    {
        $user = new User();
        $this->assertEquals(date('Y-m-d'), $user->getCreatedDate()->format('Y-m-d'));
    }

    public function testGetUpdatedDate(): void
    {
        $user = new User();
        $this->assertEquals(date('Y-m-d'), $user->getUpdatedDate()->format('Y-m-d'));
    }

    /**
     * Test user validation.
     */
    public function testValidation(): void
    {
        $this->specify('Email is required', function () {
            $user = new User();
            $user->setEmail(null);

            $this->getValidator()->validate($user);
        });
    }

    /**
     * @return ValidatorInterface
     */
    private function getValidator(): ValidatorInterface
    {
        return $this->tester->grabService('validator');
    }
}
