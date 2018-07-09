<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use HousingBundle\Entity\Housing;
use ToolsBundle\DataTrait\DateTrait;
use ToolsBundle\DataTrait\DeletedDateTrait;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    use DateTrait;
    use DeletedDateTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\Housing", mappedBy="proprietary")
     */
    private $housings;

    /**
     * @ORM\OneToMany(targetEntity="AtypikHouseBundle\Entity\Reservation", mappedBy="user")
     */
    private $reservations;

    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\UserPersonalInfos", mappedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(name="personal_info", referencedColumnName="id")
     */
    private $personalInfos;

    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\UserProfessionalInfos", mappedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(name="professional_info", referencedColumnName="id")
     */
    private $professionalInfos;

    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\UserScoreCard", mappedBy="user")
     * @ORM\JoinColumn(name="score_card", referencedColumnName="id")
     */
    private $scoreCard;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UserWishlist", mappedBy="user")
     */
    private $wichlists;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UserNotification", mappedBy="user")
     */
    private $notifications;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @var string|null
     *
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $googleId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $googleAccessToken;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_access_token", type="string", length=255, nullable=true)
     */
    protected $userAccessToken;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->housings = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->wichlists = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|Housing[]
     */
    public function getHousings()
    {
        return $this->housings;
    }

    /**
     * @param mixed $housings Set the housing for User
     *
     * @return void
     */
    public function setHousings($housings): void
    {
        $this->housings = $housings;
    }

    /**
     * @return mixed
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * @param mixed $reservations Set the reservations for User
     *
     * @return void
     */
    public function setReservations($reservations): void
    {
        $this->reservations = $reservations;
    }

    /**
     * @return null|UserPersonalInfos
     */
    public function getPersonalInfos(): ?UserPersonalInfos
    {
        return $this->personalInfos;
    }

    /**
     * @param mixed $personalInfos Set the personnalInfos for User
     *
     * @return void
     */
    public function setPersonalInfos($personalInfos): void
    {
        $this->personalInfos = $personalInfos;
    }

    /**
     * @return null|UserProfessionalInfos
     */
    public function getProfessionalInfos(): ?UserProfessionalInfos
    {
        return $this->professionalInfos;
    }

    /**
     * @param mixed $professionalInfos Set the professionalInfos for User
     *
     * @return void
     */
    public function setProfessionalInfos(UserProfessionalInfos $professionalInfos): void
    {
        $this->professionalInfos = $professionalInfos;
    }

    /**
     * @return UserScoreCard
     */
    public function getScoreCard(): UserScoreCard
    {
        return $this->scoreCard;
    }

    /**
     * @param UserScoreCard $scoreCard Set the score for User
     *
     * @return void
     */
    public function setScoreCard(UserScoreCard $scoreCard): void
    {
        $this->scoreCard = $scoreCard;
    }

    /**
     * @return mixed
     */
    public function getWichlists()
    {
        return $this->wichlists;
    }

    /**
     * @param mixed $wichlists Set the wicshlist for User
     *
     * @return void
     */
    public function setWichlists($wichlists): void
    {
        $this->wichlists = $wichlists;
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param mixed $notifications Set the notification for User
     *
     * @return void
     */
    public function setNotifications($notifications): void
    {
        $this->notifications = $notifications;
    }

    /**
     * @return null|string
     */
    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId Set the facebookId for User
     *
     * @return void
     */
    public function setFacebookId(string $facebookId): void
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return null|string
     */
    public function getFacebookAccessToken(): ?string
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param string $facebookAccessToken Set the fb token for User
     *
     * @return void
     */
    public function setFacebookAccessToken(string $facebookAccessToken): void
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return null|string
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * @param null|string $googleId Set the google id for User
     *
     * @return void
     */
    public function setGoogleId($googleId): void
    {
        $this->googleId = $googleId;
    }

    /**
     * @return null|string
     */
    public function getGoogleAccessToken(): ?string
    {
        return $this->googleAccessToken;
    }

    /**
     * @param null|string $googleAccessToken Set the google token for User
     *
     * @return void
     */
    public function setGoogleAccessToken($googleAccessToken): void
    {
        $this->googleAccessToken = $googleAccessToken;
    }

    /**
     * @return null|string
     */
    public function getUserAccessToken(): ?string
    {
        return $this->userAccessToken;
    }

    /**
     * @param null|string $userAccessToken Set the user access token for User
     *
     * @return void
     */
    public function setUserAccessToken($userAccessToken): void
    {
        $this->userAccessToken = $userAccessToken;
    }
}
