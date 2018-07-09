<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HousingBundle\Entity\Housing;

/**
 * UserWichListValue
 *
 * @ORM\Table(name="user_wich_list_value")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserWishListValueRepository")
 */
class UserWishListValue
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\UserWishlist", inversedBy="wishListValues")
     * @ORM\JoinColumn(name="wish_list_id", referencedColumnName="id")
     */
    private $wishList;

    /**
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing")
     * @ORM\JoinColumn(name="housing_id", referencedColumnName="id")
     */
    private $housing;

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
     * @return UserWishlist
     */
    public function getWishList(): UserWishlist
    {
        return $this->wishList;
    }

    /**
     * @param UserWishlist $wishList
     *
     * @return void
     */
    public function setWishList(UserWishlist $wishList): void
    {
        $this->wishList = $wishList;
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
