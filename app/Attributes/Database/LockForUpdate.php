<?php

declare(strict_types=1);

namespace App\Attributes\Database;

use Attribute;

/**
 * Attribute to mark a parameter of controller method for row-level locking.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class LockForUpdate
{
    //
}
