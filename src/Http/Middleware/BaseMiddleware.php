<?php

namespace p4scu41\BaseCRUDApi\Http\Middleware;

use Closure;

/**
 * Middleware Base Class
 *
 * @category Middleware
 * @package  p4scu41\BaseCRUDApi\Http\Middleware
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request Request instance
     * @param \Closure                 $next    Response instance
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action

        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}
