<?php

namespace App\GraphQL\Directives;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;

final class AccountDirective extends BaseDirective implements FieldResolver
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Return the currently authenticated user's account.
"""
directive @account on FIELD_DEFINITION
GRAPHQL;
    }

    /**
     * Set a field resolver on the FieldValue.
     *
     * This must call $fieldValue->setResolver() before returning
     * the FieldValue.
     *
     * @param  \Nuwave\Lighthouse\Schema\Values\FieldValue  $fieldValue
     * @return \Nuwave\Lighthouse\Schema\Values\FieldValue
     */
    public function resolveField(FieldValue $fieldValue): callable
    {
        return function (): ?Account {
            $account = Account::firstWhere('id', auth0_user()->getAuthIdentifier());

            if ($account === null) {
                return null;
            }

            return $account;
        };
    }
}
