<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ToolsBundle\DataTrait\DateTrait;

/**
 * Address
 *
 * @ORM\Table(name="address")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\AddressRepository")
 */
class Address
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
     * @var string|null
     *
     * @ORM\Column(name="street_number", type="string", length=10, nullable=true)
     */
    private $streetNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=45)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=45)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=8)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=35)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=150, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=150, nullable=true)
     */
    private $longitude;

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
     * @return Address
     */
    public function setId(int $id): Address
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get a StreetNumber
     *
     * @return null|string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set a StreetNumber
     *
     * @param null|string $streetNumber Set a new streetNumber
     *
     * @return Address
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get a Address
     *
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Set a Address
     *
     * @param string $address Set a new address
     *
     * @return Address
     */
    public function setAddress(string $address): Address
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get a City
     *
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Set a City
     *
     * @param string $city Set a new city
     *
     * @return Address
     */
    public function setCity(string $city): Address
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get a State
     *
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Set a State
     *
     * @param string $state Set a new state
     *
     * @return Address
     */
    public function setState(string $state): Address
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get a PostalCode
     *
     * @return string
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Set a PostalCode
     *
     * @param string $postalCode Set a new postalCode
     *
     * @return Address
     */
    public function setPostalCode(string $postalCode): Address
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get a Country
     *
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Set a Country
     *
     * @param string $country Set a new country
     *
     * @return Address
     */
    public function setCountry(string $country): Address
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get a Latitude
     *
     * @return string
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * Set a Latitude
     *
     * @param string $latitude Set a new latitude
     *
     * @return Address
     */
    public function setLatitude(string $latitude): Address
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get a Longitude
     *
     * @return string
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * Set a Longitude
     *
     * @param string $longitude Set a new longitude
     *
     * @return Address
     */
    public function setLongitude(string $longitude): Address
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the latitude and fongitude for address
     *
     * @return null|string
     */
    public function getLatAndLong(): ?string
    {
        return $this->latitude.', '.$this->longitude;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getStreetNumber().' '.$this->getAddress().' '.$this->getPostalCode().' '.$this->getCity();
    }
}
