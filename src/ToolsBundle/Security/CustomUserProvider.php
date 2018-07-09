<?php
namespace ToolsBundle\Security;

use FOS\UserBundle\Doctrine\UserManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;

class CustomUserProvider extends BaseClass
{
    private $userInformation;
    private $session;

    /**
     * CustomUserProvider constructor.
     *
     * @param UserManager $userManager     Instance class with User Manager
     * @param UserInfos   $userInformation Instance class with User Infos
     * @param Session     $session         Instance class with Session
     * @param array       $properties      Instance class with properties
     */
    public function __construct(UserManager $userManager, UserInfos $userInformation, Session $session, array $properties)
    {
        parent::__construct($userManager, $properties);
        $this->userInformation = $userInformation;
        $this->session = $session;
    }

    /**
     * @param UserInterface         $user     User to connect
     * @param UserResponseInterface $response Response of the login
     *
     * @return void
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getEmail();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy([$property => $username])) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    /**
     *
     * @param UserResponseInterface $response Response of the login
     *
     * @return \FOS\UserBundle\Model\UserInterface|null
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getEmail();
        if (null === $username) {
            $this->session
                ->getFlashBag()
                ->add(
                    'warning',
                    sprintf(
                        "Impossible de se connecter avec %s.",
                        ucfirst($response->getResourceOwner()->getName())
                    )
                );
            throw new AccountNotLinkedException(sprintf("User '%s' not found.", $username));
        }
        $user = $this->userManager->findUserByUsernameOrEmail($username);
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            $user->setUsername($username);
            $user->setEmail($username);
            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userInformation->setPersonalInfo($user, $response);
            $this->userManager->updateUser($user);
            return $user;
        }
        $username = $response->getEmail();
        $user = $this->userManager->findUserByUsernameOrEmail($username);
        if (null === $user || null === $username) {
            throw new AccountNotLinkedException(sprintf("User '%s' not found.", $username));
        }
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        $user->$setter($response->getAccessToken());
        return $user;
    }
}