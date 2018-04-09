<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * UserFixture.
 */
class UserFixture extends Fixture
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param UserRepositoryInterface      $userRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserRepositoryInterface $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@example.net');
        $admin->setPhone('+841208667413');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->encoder->encodePassword($admin, 'admin'));
        $admin->setEnabled(true);

        $this->userRepository->save($admin);
    }
}
