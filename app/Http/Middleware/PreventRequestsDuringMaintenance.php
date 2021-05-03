<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /* Tato trida je soucasti frameworku Laravel */

    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     * Tato trida je soucasti frameworku Laravel
     * @var array
     */
    protected $except = [
        //
    ];
}
