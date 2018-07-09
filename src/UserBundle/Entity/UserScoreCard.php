<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserScoreCard
 *
 * @ORM\Table(name="user_score_card")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserScoreCardRepository")
 */
class UserScoreCard
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User", inversedBy="scoreCard")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=45)
     */
    private $grade = 'rookie';

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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return void
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     *
     * @return void
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    /**
     * @return string
     */
    public function getGrade(): string
    {
        return $this->grade;
    }

    /**
     * @param string $grade
     *
     * @return void
     */
    public function setGrade(string $grade): void
    {
        $this->grade = $grade;
    }
}
