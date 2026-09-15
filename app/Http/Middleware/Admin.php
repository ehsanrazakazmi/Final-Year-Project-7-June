<?php

namespace App\Http\Middleware;

class Admin extends EnsureRole
{
    protected function role(): string
    {
        return 'admin';
    }
}
