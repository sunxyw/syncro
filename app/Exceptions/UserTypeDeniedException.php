<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;

class UserTypeDeniedException extends Exception implements ClientAware
{
    public function __construct()
    {
        parent::__construct('Your user type is not allowed to perform this action');
    }

    public function isClientSafe(): bool
    {
        return true;
    }
}
