<?php

namespace HousingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ToolsBundle\DataTrait\DateTrait;

/**
 * HousingDetailValue
 *
 * @ORM\Table(name="housing_detail_value")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingDetailValueRepository")
 */
class HousingDetailValue
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
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\HousingDetail", inversedBy="values")
     * @ORM\JoinColumn(name="detail_id", referencedColumnName="id")
     */
    private $detail;

    /**
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing", inversedBy="details")
     * @ORM\JoinColumn(name="housing_id", referencedColumnName="id")
     */
    private $housing;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;


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
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     *
     * @return void
     */
    public function setDetail($detail): void
    {
        $this->detail = $detail;
    }

    /**
     * @return mixed
     */
    public function getHousing()
    {
        return $this->housing;
    }

    /**
     * @param mixed $housing
     *
     * @return void
     */
    public function setHousing($housing): void
    {
        $this->housing = $housing;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return void
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
