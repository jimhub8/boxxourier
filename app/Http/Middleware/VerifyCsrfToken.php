<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'csv/import',
        'userDateExpo',
        'shipmentExpo',
        'userExpo',
        'deriverdExpo',
        'customersExpo',
        'branchesExpo',
        'agentsExpo',
        'cancledExpo',
        'pendingExpo',
        'bookingExpo',
        'approvedExpo',
        'dispatchedExpo',
        'userDateExpo',
        'DriverReport',
        'pod/*',
        'displayReport',
        'logout',
        'confirmation',
        'validation',
        'register_url',
    ];
}
