<?php

namespace App\Http\Middleware;

class resident extends EnsureRole
{
    protected function role(): string
    {
        return 'resident';
    }
}
