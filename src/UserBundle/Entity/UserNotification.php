<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HousingBundle\Entity\Housing;
use ToolsBundle\DataTrait\DateTrait;

/**
 * UserNotification
 *
 * @ORM\Table(name="user_notification")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserNotificationRepository")
 */
class UserNotification
{
    use DateTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="state", type="boolean")
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=45)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing")
     * @ORM\JoinColumn(name="housing_id", referencedColumnName="id")
     */
    private $housing;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="target_user_id", referencedColumnName="id")
     */
    private $targetUser;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * Get a Id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set a Id
     *
     * @param int $id Set a new id
     *
     * @return UserNotification
     */
    public function setId(int $id): UserNotification
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get a State
     *
     * @return bool
     */
    public function isState(): ?bool
    {
        return $this->state;
    }

    /**
     * Set a State
     *
     * @param bool $state Set a new state
     *
     * @return UserNotification
     */
    public function setState(bool $state): UserNotification
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get a Type
     *
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set a Type
     *
     * @param string $type Set a new type
     *
     * @return UserNotification
     */
    public function setType(string $type): UserNotification
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get a User
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set a User
     *
     * @param mixed $user Set a new user
     *
     * @return UserNotification
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get a Housing
     *
     * @return mixed
     */
    public function getHousing()
    {
        return $this->housing;
    }

    /**
     * Set a Housing
     *
     * @param mixed $housing Set a new housing
     *
     * @return UserNotification
     */
    public function setHousing($housing)
    {
        $this->housing = $housing;

        return $this;
    }

    /**
     * Get a TargetUser
     *
     * @return mixed
     */
    public function getTargetUser()
    {
        return $this->targetUser;
    }

    /**
     * Set a TargetUser
     *
     * @param mixed $targetUser Set a new targetUser
     *
     * @return UserNotification
     */
    public function setTargetUser($targetUser)
    {
        $this->targetUser = $targetUser;

        return $this;
    }

    /**
     * Get a Message
     *
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set a Message
     *
     * @param string $message Set a new message
     *
     * @return UserNotification
     */
    public function setMessage(string $message): UserNotification
    {
        $this->message = $message;

        return $this;
    }
}
