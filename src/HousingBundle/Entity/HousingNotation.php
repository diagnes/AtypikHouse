<?php

namespace HousingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ToolsBundle\DataTrait\DateTrait;

/**
 * HousingNotation
 *
 * @ORM\Table(name="housing_notation")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingNotationRepository")
 */
class HousingNotation
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
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\HousingNotationType")
     * @ORM\JoinColumn(name="notation_type_id", referencedColumnName="id")
     */
    private $notationType;

    /**
     * @ORM\ManyToOne(targetEntity="HousingBundle\Entity\Housing", inversedBy="notations")
     * @ORM\JoinColumn(name="housing_id", referencedColumnName="id")
     */
    private $housing;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
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
     * @return HousingNotationType
     */
    public function getNotationType(): HousingNotationType
    {
        return $this->notationType;
    }

    /**
     * @param HousingNotationType $notationType
     *
     * @return void
     */
    public function setNotationType(HousingNotationType $notationType): void
    {
        $this->notationType = $notationType;
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

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @return void
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}
