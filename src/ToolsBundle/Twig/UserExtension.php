<?php

namespace ToolsBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Intl\Intl;
use Twig_Environment;
use UserBundle\Entity\User;
use UserBundle\Entity\UserNotification;

/**
 * Twig Extension UserExtension
 */
class UserExtension extends \Twig_Extension
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * UserExtension constructor.
     *
     * @param EntityManager $em Get the entityManager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Create list of filters for reservation
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('natinality', [$this, 'natinalityFilter']),
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
            new \Twig_SimpleFunction('getVisibleNotifications', [$this, 'getVisibleNotifications']),
        ];
    }

    /**
     * Get all user not seen notification
     *
     * @param User $user Get the user
     *
     * @return UserNotification[]
     */
    public function getVisibleNotifications(User $user)
    {
        return $this->em->getRepository(UserNotification::class)->findBy(['targetUser' => $user, 'state' => 1]);
    }

    /**
     * Get visible notification
     *
     * @param User $user Get the user
     *
     * @return int
     */
    public function visibleNotifFilter(User $user)
    {
        return $this->em->getRepository(User::class)->visbleNotification($user->getId());
    }

    /**
     * Filter for compute the total priceStay for a reservation
     *
     * @param string $nationality Get the full name nationality
     *
     * @return float
     */
    public function natinalityFilter(string $nationality)
    {
        return $nationality ? Intl::getRegionBundle()->getCountryName($nationality) : $nationality;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'atypikhouse_user_extension';
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
        return [];
    }
}