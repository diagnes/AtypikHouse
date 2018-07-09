<?php

namespace HousingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ToolsBundle\DataTrait\DateTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * HousingDetailType
 *
 * @ORM\Table(name="housing_type")
 * @ORM\Entity(repositoryClass="HousingBundle\Repository\HousingTypeRepository")
 *
 * @UniqueEntity("name")
 */
class HousingType
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
     * @ORM\OneToMany(targetEntity="HousingBundle\Entity\Housing", mappedBy="type")
     */
    private $housings;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     *
     * @ORM\Column(name="slug", type="string", length=45)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * HousingType constructor.
     */
    public function __construct()
    {
        $this->housings = new ArrayCollection();
    }

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
    public function getHousings()
    {
        return $this->housings;
    }

    /**
     * @param mixed $housings
     *
     * @return void
     */
    public function setHousings($housings): void
    {
        $this->housings = $housings;
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
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
