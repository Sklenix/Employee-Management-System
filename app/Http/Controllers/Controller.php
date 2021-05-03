<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
    /* Nazev souboru: Controller.php */
    /* Trida, z ktere ostatni kontrolery pouze dedi. Poskytuje zakladni funkcionality kontroleru viz https://laravel.com/docs/8.x/controllers */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
