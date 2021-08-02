<?php

namespace FleetCart\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'checkout_mob','OrderHistory','QrCodeScann','customerlist', 'scanner','sitting_arrangement_save', 'directory'
    ];
}
