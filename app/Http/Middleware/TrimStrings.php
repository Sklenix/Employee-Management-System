<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /* Tato trida je soucasti frameworku Laravel */

    /**
     * The names of the attributes that should not be trimmed.
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
