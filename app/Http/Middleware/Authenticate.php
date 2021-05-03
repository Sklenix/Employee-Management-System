<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /* Tato trida je soucasti frameworku Laravel */

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('renderCompanyLogin');
        }
    }
}
