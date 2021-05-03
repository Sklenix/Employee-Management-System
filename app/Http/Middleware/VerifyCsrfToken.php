<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /* Tato trida je soucasti frameworku Laravel */

    /**
     * The URIs that should be excluded from CSRF verification.
     * @var array
     */
    protected $except = [
        //
    ];
}
