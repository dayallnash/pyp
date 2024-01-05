<?php

namespace App\DBAL\Type;

/**
 * Class EnumYesNo.
 *
 * @see EnumNoYes
 */
class EnumYesNo extends AbstractEnumType
{
    protected string $name = 'enum_yes_no';
    protected array $values = ['y', 'n', null];
}
