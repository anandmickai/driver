<?php

namespace App\Http\Middleware;

use App\Services\HeadersService;
use Closure;

class HeadersCheck
{
    protected $headerCheck;

    public function __construct(HeadersService $headers)
    {
        $this->headerCheck = $headers;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headerValidate = $this->headerCheck->validateRequestHeader($request);
        if ($headerValidate)
        {
            return response()->fail($headerValidate, 'E_PRECONDITION');
        }
        return $next($request);
    }
}
