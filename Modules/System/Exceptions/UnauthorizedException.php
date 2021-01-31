<?php

namespace Modules\System\Exceptions;

use Spatie\Permission\Exceptions\UnauthorizedException as BaseUnauthorizedException;

class UnauthorizedException extends BaseUnauthorizedException
{
    public static function forSuperadmin() : self
    {
        $message = 'User does not have the right super admin permission.';

        $exception = new static(403, $message, null, []);

        return $exception;
    }
}
