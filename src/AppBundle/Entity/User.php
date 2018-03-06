<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedDate;

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
}
