<?php

namespace AppBundle\Entity;

use PHPUnit\Framework\TestCase;

/**
 * UserTest.
 */
class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    protected function setUp()
    {
        $this->user = new User();
    }

    public function testEmailProperty()
    {
        // Correct value
        $email = 'test@example.net';
        $this->user->setEmail($email);
        $this->assertInternalType('string', $this->user->getEmail());
        $this->assertEquals($email, $this->user->getEmail());
        $this->assertEquals($email, $this->user->getUsername());

        // Allow null value
        $email = null;
        $this->user->setEmail($email);
        $this->assertNull($this->user->getEmail());
        $this->assertNull($this->user->getUsername());
    }

    public function testStatusProperty()
    {
        // Correct value
        $status = 'A';
        $this->user->setStatus($status);
        $this->assertInternalType('string', $this->user->getStatus());
        $this->assertEquals($status, $this->user->getStatus());
    }

    public function testPhoneProperty()
    {
        // Correct value
        $phone = '+841208557847';
        $this->user->setPhone($phone);
        $this->assertInternalType('string', $this->user->getPhone());
        $this->assertEquals($phone, $this->user->getPhone());
    }

    public function testCreatedDateProperty()
    {
        // Correct value
        $this->user->setCreatedDate();
        $this->assertInstanceOf(\DateTime::class, $this->user->getCreatedDate());
    }

    public function testUpdatedDateProperty()
    {
        // Correct value
        $this->user->setUpdatedDate();
        $this->assertInstanceOf(\DateTime::class, $this->user->getUpdatedDate());
    }
}
