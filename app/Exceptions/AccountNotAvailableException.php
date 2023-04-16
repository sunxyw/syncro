<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;

class AccountNotAvailableException extends Exception implements ClientAware
{
    public function __construct()
    {
        parent::__construct('Account not created yet');
    }

    public function isClientSafe(): bool
    {
        return true;
    }
}
