<?php

namespace UserBundle\Enum;

use ToolsBundle\Enum\AbstractEnum;

class UserRoleEnum extends AbstractEnum
{
    public const ROLE_IT = 'ROLE_IT';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_PROPRIETARY = 'ROLE_PROPRIETARY';
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @return array
     */
    public static function toAssoc(): array
    {
        return [
            'Role It' => self::ROLE_IT,
            'Role admin' => self::ROLE_ADMIN,
            'Role proprietaire' => self::ROLE_PROPRIETARY,
            'Role utilisateur' => self::ROLE_USER,
        ];
    }
}