<?php

namespace AtypikHouseBundle\Enum;

use ToolsBundle\Enum\AbstractEnum;

class NotificationTypeEnum extends AbstractEnum
{
    // Type
    public const USER = 'user';
    public const MESSAGE = 'message';
    public const RESERVATION = 'validated';
    public const ADMIN = 'admin';
    public const PROPRIETARY = 'proprietary';
    public const HOUSING = 'housing';
    public const BLOG = 'blog';

    /**
     *
     * @return array
     */
    public static function toAssoc(): array
    {
        return [
            self::USER => 'User',
            self::MESSAGE => 'Message',
            self::RESERVATION => 'Reservation',
            self::ADMIN => 'Admin',
            self::PROPRIETARY => 'Proprietary',
            self::HOUSING => 'Housing',
            self::BLOG => 'Blog',
        ];
    }

    /**
     * @param string $state Get the state token
     *
     * @return array|string
     */
    public static function getLabels($state): string
    {
        $states = static::toAssoc();

        return $states[$state];
    }
}