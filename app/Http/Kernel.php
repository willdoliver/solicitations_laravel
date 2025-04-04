<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'verificar-login' => \App\Http\Middleware\VerificarLogin::class,
    ];
}