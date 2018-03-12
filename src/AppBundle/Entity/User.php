<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    public const STATUS_ACTIVE = 'A';
    public const STATUS_SUSPENDED = 'S';
    public const STATUS_REMOVED = 'X';
    public const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_SUSPENDED,
        self::STATUS_REMOVED,
    ];

    public const DISABLED = 0;
    public const ENABLED = 1;

    public const PHONE_FORMAT = '/^(\+)\d{7,16}$/';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=1, options={"fixed": true})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=20, unique=true, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedDate;

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $email = $email ?? null;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return User
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return User
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }

    /**
     * @ORM\PrePersist
     *
     * @return User
     */
    public function setCreatedDate(): self
    {
        $this->createdDate = new \DateTime();

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate(): \DateTime
    {
        return $this->updatedDate;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return User
     */
    public function setUpdatedDate(): self
    {
        $this->updatedDate = new \DateTime();

        return $this;
    }

    /**
     * @param ClassMetadata $metadata
     *
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addConstraints(
            [
                new UniqueEntity(['fields' => ['email', 'phone']]),
            ]
        );

        // Email
        $metadata->addPropertyConstraints(
            'email',
            [
                new NotBlank(),
                new Email(),
            ]
        );

        // Password
        $metadata->addPropertyConstraints(
            'plainPassword',
            [
                new NotBlank(['groups' => 'Create']),
            ]
        );

        // Phone
        $metadata->addPropertyConstraints(
            'phone',
            [
                new Regex(['pattern' => self::PHONE_FORMAT]),
            ]
        );
    }
}
