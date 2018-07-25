<?php

namespace UserBundle\Enum;

use ToolsBundle\Enum\AbstractEnum;

class UserGenderEnum extends AbstractEnum
{
    public const MAN = 'M';
    public const WOMEN = 'W';

    /**
     * @return array
     */
    public static function toAssoc(): array
    {
        return [
            self::MAN => 'Mister',
            self::WOMEN => 'Miss',
        ];
    }
}