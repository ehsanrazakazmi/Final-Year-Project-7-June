<?php

namespace App\Http\Middleware;

class technician extends EnsureRole
{
    protected function role(): string
    {
        return 'technician';
    }
}
