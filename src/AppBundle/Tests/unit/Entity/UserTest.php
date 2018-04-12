<?php

namespace AppBundle\Entity;

/**
 * UserTest.
 */
class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \AppBundle\UnitTester
     */
    protected $tester;

    /**
     * Test setter and getter methods.
     */
    public function testSetterAndGetter(): void
    {
        $user = new User();

        // Id
        $this->assertNull($user->getId());
        $user->setId(1);
        $this->assertEquals(1, $user->getId());

        // Email
        $this->assertNull($user->getEmail());
        $user->setEmail('test@test.net');
        $this->assertEquals('test@test.net', $user->getEmail());

        // Password
        $this->assertNull($user->getPassword());
        $user->setPassword('123456');
        $this->assertEquals('123456', $user->getPassword());

        // Status
        $this->assertEquals($user->getStatus(), User::STATUS_ACTIVE);
        $user->setStatus(User::STATUS_SUSPENDED);
        $this->assertEquals(User::STATUS_SUSPENDED, $user->getStatus());

        // Enabled
        $this->assertEquals($user->isEnabled(), User::DISABLED);
        $user->setEnabled(User::ENABLED);
        $this->assertEquals(User::ENABLED, $user->isEnabled());

        // Phone
        $this->assertNull($user->getPhone());
        $user->setPhone('+841208772134');
        $this->assertEquals('+841208772134', $user->getPhone());

        // Created Date
        $this->assertEquals(date('Y-m-d'), $user->getCreatedDate()->format('Y-m-d'));

        // Updated Date
        $this->assertEquals(date('Y-m-d'), $user->getUpdatedDate()->format('Y-m-d'));
    }

    /**
     * Test user validation.
     */
    public function testValidation(): void
    {
    }
}
