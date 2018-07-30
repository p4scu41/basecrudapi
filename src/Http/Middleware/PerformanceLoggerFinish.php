<?php

namespace p4scu41\BaseCRUDApi\Http\Middleware;

use p4scu41\BaseCRUDApi\Support\ReflectionSupport;
use Closure;

/**
 * PerformanceLoggerFinish Middleware
 *
 * @category Middleware
 * @package  p4scu41\BaseCRUDApi\Http\Middleware
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-07-27
 */
class PerformanceLoggerFinish extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $route    = $request->route();

        if (!empty($route)) {
            $action     = $route->getAction(); // $route->action (array)
            $controller = $route->controller; // $route->getController() (instance of current Controller)

            if (!empty($controller)) {
                if (ReflectionSupport::hasProperty($controller, 'is_tracking_performance') &&
                    ReflectionSupport::hasProperty($controller, 'except_track_performance')) {
                    // $controller[0] => class, $controller[1] => method
                    $method = isset($action['controller']) ? explode('@', $action['controller']) : 'laravel';

                    if ($controller->is_tracking_performance &&
                        !in_array($method[1] ?: $method, $controller->except_track_performance)) {
                        $controller->performance->finish();
                        $controller->performance->getInfo();
                        $controller->performance->saveAll();
                    }
                }
            }
        }

        return $response;
    }
}
