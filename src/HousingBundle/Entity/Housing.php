<?php

namespace HousingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ToolsBundle\DataTrait\DateTrait;
use ToolsBundle\DataTrait\DeletedDateTrait;
use UserBundle\Entity\Address;
use UserBundle\Entity\User;

/**
 * Housing
 *
 * @ORM\Table(name="housing")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingRepository")
 */
class Housing
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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="housings")
     * @ORM\JoinColumn(name="proprietary", referencedColumnName="id")
     */
    private $proprietary;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=45)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=15)
     */
    private $state;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="string", length=15, options={"default" : 0})
     */
    private $visible;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @ORM\Column(name="slug", type="string", length=45, unique=true)
     */
    private $slug;

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\HousingType", inversedBy="housings", cascade={"persist"})
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\HousingUndisponibility", mappedBy="housing", cascade={"persist"})
     */
    private $undisponibility;

    /**
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\HousingDetailValue", mappedBy="housing", cascade={"persist"})
     */
    private $details;

    /**
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\HousingNotation", mappedBy="housing", cascade={"persist"})
     */
    private $notations;

    /**
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\HousingDocument", mappedBy="housing", cascade={"persist"})
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\HousingImages", mappedBy="housing", cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="AtypikHouseBundle\Entity\Reservation", mappedBy="housing")
     */
    private $reservations;

    /**
     * Housing constructor.
     */
    public function __construct()
    {
        $this->undisponibility = new ArrayCollection();
        $this->details = new ArrayCollection();
        $this->notations = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id Set id
     *
     * @return void
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProprietary()
    {
        return $this->proprietary;
    }

    /**
     * @param mixed $proprietary Set proprietary
     *
     * @return void
     */
    public function setProprietary($proprietary)
    {
        $this->proprietary = $proprietary;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title Set title
     *
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state Set state
     *
     * @return void
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(bool $visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price Set price
     *
     * @return void
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug Set slug
     *
     * @return void
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address Set Address
     *
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return HousingType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type Set type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return ArrayCollection|HousingUndisponibility[]
     */
    public function getUndisponibility()
    {
        return $this->undisponibility;
    }

    /**
     * @param mixed $undisponibility
     */
    public function setUndisponibility($undisponibility)
    {
        $this->undisponibility = $undisponibility;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param null|HousingDetail $details Set details
     *
     * @return void
     */
    public function setDetails(?HousingDetail $details)
    {
        $this->details = $details;
    }

    /**
     * @return mixed
     */
    public function getNotations()
    {
        return $this->notations;
    }

    /**
     * @param mixed $notations SetNoations
     *
     * @return void
     */
    public function setNotations($notations)
    {
        $this->notations = $notations;
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param mixed $documents Set documents
     *
     * @return void
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images Set Images
     *
     * @return void
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return mixed
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * @param mixed $reservations Set reservation
     *
     * @return void
     */
    public function setReservations($reservations)
    {
        $this->reservations = $reservations;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'housing';
    }
}
