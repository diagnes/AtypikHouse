<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class SocialNetworkController
 */
class SocialNetworkController
{
    /**
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     */
    public function getJwtTokenFacebookAction()
    {
        return new Response('test');
    }

    /**
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     */
    public function getJwtTokenGoogleAction()
    {
        return new Response('test');
    }
}