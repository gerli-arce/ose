<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanySelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('current_company_id')) {
            return redirect()->route('select.company');
        }

        return $next($request);
    }
}
