<?php

namespace UserBundle\Entity;

use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;
use ToolsBundle\DataTrait\DateTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserPersonalInfos
 *
 * @ORM\Table(name="user_personal_infos")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserPersonalInfosRepository")
 */
class UserPersonalInfos
{
    use DateTrait;

    /**
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var User|null
     *
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User", inversedBy="personalInfos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Media
     *
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $image;

    /**
     *
     * @var string|null
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=45)
     */
    private $firstname;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=45)
     */
    private $lastname;

    /**
     *
     * @var \DateTime|null
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     *
     * @var string|null
     *
     * @ORM\Column(name="birth_location", type="string", length=45, nullable=true)
     */
    private $birthLocation;

    /**
     *
     * @var null|Address
     *
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     *
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     *
     * @var string|null
     *
     * @Assert\Valid()
     * @Assert\Length(min = 10, max = 10)
     * @Assert\Regex(pattern="/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/", message="Phone number it's number only")
     *
     * @ORM\Column(name="phone_number", type="string", length=10, nullable=true)
     */
    private $phoneNumber;

    /**
     *
     * @var string|null
     *
     * @ORM\Column(name="profession", type="string", length=45, nullable=true)
     */
    private $profession;

    /**
     *
     * @var string|null
     *
     * @ORM\Column(name="nationality", type="string", length=45, nullable=true)
     */
    private $nationality;


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
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     *
     * @param User $user User params for personnalInfos
     *
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }


    /**
     *
     * @return null|string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     *
     * @param null|string $gender Gender params for personnalInfos
     *
     * @return void
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     *
     * @return string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     *
     * @param null|string $firstname Firstname params for personnalInfos
     *
     * @return void
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     *
     * @return string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     *
     * @param null|string $lastname Firstname params for personnalInfos
     *
     * @return void
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     *
     * @return \DateTime|null
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     *
     * @param \DateTime|null $birthDate BirthDate params for personnalInfos
     *
     * @return void
     */
    public function setBirthDate($birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     *
     * @return null|string
     */
    public function getBirthLocation(): ?string
    {
        return $this->birthLocation;
    }

    /**
     *
     * @param null|string $birthLocation BirthLocation params for personnalInfos
     *
     * @return void
     */
    public function setBirthLocation($birthLocation): void
    {
        $this->birthLocation = $birthLocation;
    }

    /**
     *
     * @return null|Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     *
     * @param null|Address $address Address params for personnalInfos
     *
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     *
     * @param null|string $description Description params for personnalInfos
     *
     * @return void
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     *
     * @return null|string
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     *
     * @param null|string $phoneNumber PhoneNumber params for personnalInfos
     *
     * @return void
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     *
     * @return null|string
     */
    public function getProfession(): ?string
    {
        return $this->profession;
    }

    /**
     *
     * @param null|string $profession Profession params for personnalInfos
     *
     * @return void
     */
    public function setProfession($profession): void
    {
        $this->profession = $profession;
    }

    /**
     *
     * @return null|string
     */
    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    /**
     *
     * @param null|string $nationality Nationality params for personnalInfos
     *
     * @return void
     */
    public function setNationality($nationality): void
    {
        $this->nationality = $nationality;
    }

    /**
     * Get a Image
     *
     * @return Media
     */
    public function getImage(): ?Media
    {
        return $this->image;
    }

    /**
     * Set a Image
     *
     * @param Media $image Set a new image
     *
     * @return UserPersonalInfos
     */
    public function setImage(Media $image): UserPersonalInfos
    {
        $this->image = $image;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFirstAndLastName(): ?string
    {
        return $this->firstname.' '.$this->lastname;
    }
}
