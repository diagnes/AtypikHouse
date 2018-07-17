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
     * @ORM\Column(name="id",               type="integer")
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
     * @ORM\Column(name="additional_address", type="string", length=45, nullable=true)
     */
    private $additionalAddress;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id Set the  for Address
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    /**
     * @param null|string $streetNumber Set the street number for Address
     *
     * @return void
     */
    public function setStreetNumber($streetNumber): void
    {
        $this->streetNumber = $streetNumber;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address Set the address for Address
     *
     * @return void
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city Set the city for Address
     *
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state Set the state for Address
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
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode Set the postalCode for Address
     *
     * @return void
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country Set the country for Address
     *
     * @return void
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return null|string
     */
    public function getAdditionalAddress(): ?string
    {
        return $this->additionalAddress;
    }

    /**
     * @param null|string $additionalAddress Set the additionalAddress for Address
     *
     * @return void
     */
    public function setAdditionalAddress($additionalAddress): void
    {
        $this->additionalAddress = $additionalAddress;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getStreetNumber().' '.$this->getAddress().' '.$this->getPostalCode().' '.$this->getCity();
    }
}
