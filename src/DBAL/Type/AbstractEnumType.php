<?php

namespace App\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

/**
 * Class AbstractEnumType.
 */
abstract class AbstractEnumType extends Type
{
    protected string $name;
    protected array $values = [];

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $enumValues = array_map(
            static function ($val) {
                return "'".$val."'";
            },
            $this->values
        );

        return 'ENUM('.implode(', ', $enumValues).')';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->values, true)) {
            throw new InvalidArgumentException(sprintf('Invalid \'%s\' value.', $this->name));
        }

        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
