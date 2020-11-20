<?php
namespace Psalm\Type\Atomic;

class TFalse extends TBool
{
    public function __toString(): string
    {
        return 'false';
    }

    public function getKey(bool $include_extra = true): string
    {
        return 'false';
    }

    public function canBeFullyExpressedInPhp(int $php_major_version, int $php_minor_version): bool
    {
        return false;
    }
}
