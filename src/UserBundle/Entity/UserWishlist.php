<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserWishlist
 *
 * @ORM\Table(name="user_wishlist")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserWishlistRepository")
 */
class UserWishlist
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
     * @ORM\Column(name="name", type="string", length=125)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="wichlists")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UserWishListValue", mappedBy="wishList")
     */
    private $wishListValues;

    /**
     * UserWishlist constructor.
     */
    public function __construct()
    {
        $this->wishListValues = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return mixed
     */
    public function getWishListValues()
    {
        return $this->wishListValues;
    }

    /**
     * @param mixed $wishListValues
     *
     * @return void
     */
    public function setWishListValues($wishListValues): void
    {
        $this->wishListValues = $wishListValues;
    }
}
