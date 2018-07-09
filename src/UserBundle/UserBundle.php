<?php

namespace UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent(): string
    {
        return 'FOSUserBundle';
    }
}
