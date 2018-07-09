<?php

namespace UserBundle\Enum;

use ToolsBundle\Enum\AbstractEnum;

class UserGenderEnum extends AbstractEnum
{
    public const MAN = 'Mr';
    public const WOMEN = 'Mme';

    /**
     * @return array
     */
    public static function toAssoc(): array
    {
        return [
            self::MAN => 'Man',
            self::WOMEN => 'Women',
        ];
    }
}