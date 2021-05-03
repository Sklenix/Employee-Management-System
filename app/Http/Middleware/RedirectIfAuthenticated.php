<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated {
    /* Nazev souboru: RedirectIfAuthenticated.php */
    /* Autor uprav(metoda handle): Pavel Sklenář (xsklen12)*/
    /* Tato trida slouzi k presmerovani uzivatelu na jejich domovske stranky na zaklade jejich role */
    /* Tato trida je soucasti frameworku Laravel a byla upravena (metoda handle) */

    /* Na zaklade funkce handle bude uzivatel presmerovan na jeho domovskou stranku (na zaklade jeho role) */
    public function handle(Request $request, Closure $next, $guard = NULL) {
        if ($guard == "admin" && Auth::guard($guard)->check()) {
            return redirect('/admin/dashboard/');
        }
        if ($guard == "employee" && Auth::guard($guard)->check()) {
            return redirect('/employee/dashboard/');
        }
        if ($guard == "company" && Auth::guard($guard)->check()) {
            return redirect('/company/dashboard/');
        }
        return $next($request);
    }
}
