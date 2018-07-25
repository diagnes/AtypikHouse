<?php

namespace ToolsBundle\Twig;

use AtypikHouseBundle\Entity\Reservation;
use AtypikHouseBundle\Enum\ReservationStateEnum;
use AtypikHouseBundle\Service\ReservationAdminManager;
use AtypikHouseBundle\Service\ReservationManager;
use HousingBundle\Entity\Housing;
use HousingBundle\Entity\HousingNotation;
use HousingBundle\Service\HousingNotationManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Twig_Environment;

/**
 * Twig Extension ReservationExtension
 */
class NotationExtension extends \Twig_Extension
{

    /**
     * @var HousingNotationManager $notationManager
     */
    private $notationManager;

    /**
     * NotationExtension constructor.
     *
     * @param HousingNotationManager $notationManager Get the housing notation manager
     */
    public function __construct(HousingNotationManager $notationManager)
    {
        $this->notationManager = $notationManager;
    }

    /**
     * Create list of filters for reservation
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('translateScore', [$this, 'translateScoreFilter']),
            new \Twig_SimpleFilter('scoreFormat', [$this, 'scoreFormatFilter']),
            new \Twig_SimpleFilter('averageScore', [$this, 'averageScoreFilter']),
            new \Twig_SimpleFilter('housingScore', [$this, 'housingScoreFilter']),
            new \Twig_SimpleFilter('bestNotation', [$this, 'bestNotationFilter']),
            new \Twig_SimpleFilter('scoreToStars', [$this, 'scoreToStarFilter', 'is_safe' => ['html']]),
            new \Twig_SimpleFilter('bestPart', [$this, 'bestPartFilter', 'is_safe' => ['html']]),
            new \Twig_SimpleFilter('nastyPart', [$this, 'nastyPartFilter', 'is_safe' => ['html']]),
        ];
    }

    /**
     * Create list of function for reservation
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
        ];
    }

    /**
     * Filter for translate score to string
     *
     * @param float $score Score to convert
     *
     * @return string
     */
    public function translateScoreFilter(float $score)
    {
        switch (true) {
            case $score >= 4:
                return 'Excellent';
            case $score >= 2:
                return 'Good';
            case $score > 0:
                return 'Bad';
            default:
                return '';
        }
    }

    /**
     * Filter for transform float to amoutn format
     *
     * @param float $amount Float to convert
     *
     * @return float
     */
    public function scoreFormatFilter(float $amount)
    {
        return number_format($amount, 1, '.', '');
    }

    /**
     * Filter allowed us to get average score for a notation
     *
     * @param HousingNotation $notation Get the notation to filtered
     *
     * @return float
     */
    public function averageScoreFilter(HousingNotation $notation)
    {
        return $this->notationManager->getAverage($notation);
    }

    /**
     * Filter allowed us to get average score for a notation
     *
     * @param Housing $housing Get the housing targeted for average score
     *
     * @return float
     */
    public function housingScoreFilter(Housing $housing)
    {
        return $this->notationManager->getAverageHousingScore($housing) ?? '';
    }

    /**
     * Filter allowed us to get best notation for house
     *
     * @param Housing $housing Get the housing targeted for average score
     *
     * @return string
     */
    public function bestNotationFilter(Housing $housing)
    {
        $notation = $this->notationManager->getBestHousingNotation($housing);
        if ($notation) {
            $date = (new \DateTime($notation['created_at']))->format('d/m/Y');
            $notationString = sprintf(
                '
            <blockquote class="quote-sidebar">
                <p>
                    %s.<br>
                    <span><b>%s</b> - %s, %s</span>
                </p>
            </blockquote>
            ',
                $notation['description'],
                $notation['firstname'].' '.$notation['lastname'],
                $notation['country'],
                $date
            );
            return $notationString;
        }

        return '';
    }

    /**
     * Filter allowed us to get average score for a notation
     *
     * @param float $score Score to transform
     *
     * @return float
     */
    public function scoreToStarFilter(float $score)
    {
        if (0 == $score) {
            return '';
        }
        $stars = '<span class="star">';
        for ($i = 0; $i < 5; $i++) {
            if ($score >= 1) {
                $stars .= '<i class="glyphicon glyphicon-star"></i>';
                $score--;
            } elseif ($score >= 0.5) {
                $stars .= '<i class="glyphicon glyphicon-star half"></i>';
                $score = 0;
            } else {
                $stars .= '<i class="glyphicon glyphicon-star-empty"></i>';
                $score = 0;
            }
        }
        $stars .= '</span>';
        return $stars;
    }

    /**
     * Filter allowed us to get name of the best part
     *
     * @param HousingNotation $notation Get the notation to filtered
     *
     * @return float
     */
    public function bestPartFilter(HousingNotation $notation)
    {
        return $this->notationManager->getBestPart($notation) ?: 'None';
    }


    /**
     * Filter allowed us to get name of the worst part
     *
     * @param HousingNotation $notation Get the notation to filtered
     *
     * @return float
     */
    public function nastyPartFilter(HousingNotation $notation)
    {
        return $this->notationManager->getWorstPart($notation) ?: 'None';
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'atypikhouse_reservation_extension';
    }

    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     *
     * @return void
     */
    public function initRuntime(Twig_Environment $environment)
    {
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return[];
    }
}