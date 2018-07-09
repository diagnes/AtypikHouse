<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HousingBundle\Entity\Housing;

/**
 * UserNotification
 *
 * @ORM\Table(name="user_notification")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserNotificationRepository")
 */
class UserNotification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=45)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=45)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing")
     * @ORM\JoinColumn(name="housing_id", referencedColumnName="id")
     */
    private $housing;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return void
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Housing
     */
    public function getHousing(): Housing
    {
        return $this->housing;
    }

    /**
     * @param Housing $housing
     *
     * @return void
     */
    public function setHousing(Housing $housing): void
    {
        $this->housing = $housing;
    }
}
