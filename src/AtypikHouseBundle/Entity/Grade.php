<?php

namespace AtypikHouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ToolsBundle\DataTrait\DateTrait;

/**
 * Grade
 *
 * @ORM\Table(name="grade")
 * @ORM\Entity(repositoryClass="AtypikHouseBundle\Repository\GradeRepository")
 */
class Grade
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
     * @var int
     *
     * @ORM\Column(name="score_max", type="integer")
     */
    private $scoreMax;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Set the grade Name
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
     * @param string $slug Set the slug
     *
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getScoreMax(): int
    {
        return $this->scoreMax;
    }

    /**
     * @param int $scoreMax Set the max score
     *
     * @return void
     */
    public function setScoreMax(int $scoreMax): void
    {
        $this->scoreMax = $scoreMax;
    }
}
