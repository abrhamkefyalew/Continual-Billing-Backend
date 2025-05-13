<?php

// NEW Laravel versions Style (i.e. laravel 12)
// namespace App\Http\Controllers;

// abstract class Controller
// {
//     //
// }


// OLD Laravel Version Style (i.e. less than laravel 12)
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
