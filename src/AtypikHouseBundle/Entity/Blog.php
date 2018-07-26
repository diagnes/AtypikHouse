<?php

namespace AtypikHouseBundle\Entity;

use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ToolsBundle\DataTrait\DateTrait;
use ToolsBundle\DataTrait\DeletedDateTrait;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="AtypikHouseBundle\Repository\BlogRepository")
 */
class Blog
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var Media
     *
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="EAGER")
     */
    private $image;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean", nullable=true, options={"default" : 0})
     */
    private $visible;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=45)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @ORM\Column(name="slug", type="string", length=45, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

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
     * @return Blog
     */
    public function setId(int $id): Blog
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Get a Author
     *
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set a Author
     *
     * @param User $author Set an author
     *
     * @return Blog
     */
    public function setAuthor(?User $author): Blog
    {
        $this->author = $author;

        return $this;
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
     * @return Blog
     */
    public function setImage(Media $image): Blog
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get a Title
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set a Title
     *
     * @param string $title Set a new title
     *
     * @return Blog
     */
    public function setTitle(string $title): Blog
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get a Slug
     *
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set a Slug
     *
     * @param string $slug Set a new slug
     *
     * @return Blog
     */
    public function setSlug(string $slug): Blog
    {
        $this->slug = $slug;

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
     * @return Blog
     */
    public function setDescription(string $description): Blog
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get a Content
     *
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set a Content
     *
     * @param string $content Set a new content
     *
     * @return Blog
     */
    public function setContent(string $content): Blog
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get a Visible
     *
     * @return bool
     */
    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    /**
     * Set a Visible
     *
     * @param bool $visible Set a new visible
     *
     * @return Blog
     */
    public function setVisible(bool $visible): Blog
    {
        $this->visible = $visible;

        return $this;
    }
}
