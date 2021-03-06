<?php

namespace HousingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ToolsBundle\DataTrait\DateTrait;
use ToolsBundle\DataTrait\DeletedDateTrait;

/**
 * HousingNotationType
 *
 * @ORM\Table(name="housing_notation_type")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingNotationTypeRepository")
 */
class HousingNotationType
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\HousingNotationValue", mappedBy="notationType", cascade={"all"}, fetch="EAGER")
     */
    private $notations;

    /**
     * HousingNotationType constructor.
     */
    public function __construct()
    {
        $this->notations = new ArrayCollection();
    }

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
     * @return HousingNotationType
     */
    public function setId(int $id): HousingNotationType
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get a Name
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set a Name
     *
     * @param string $name Set a new name
     *
     * @return HousingNotationType
     */
    public function setName(string $name): HousingNotationType
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get a Description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set a Description
     *
     * @param string $description Set a new description
     *
     * @return HousingNotationType
     */
    public function setDescription(string $description): HousingNotationType
    {
        $this->description = $description;

        return $this;
    }
}
