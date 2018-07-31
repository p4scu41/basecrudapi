<?php

namespace p4scu41\BaseCRUDApi\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\JWTAuth;

/**
 * JWTAuthValidation Middleware
 *
 * @category Middleware
 * @package  p4scu41\BaseCRUDApi\Http\Middleware
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-07-30
 */
class JWTAuthValidation extends BaseMiddleware
{
    /**
     * The JWT Authenticator.
     *
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new BaseMiddleware instance.
     *
     * @param  \Tymon\JWTAuth\JWTAuth  $auth
     *
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
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
        try {
            if (! $this->auth->parser()->setRequest($request)->hasToken()) {
                throw new TokenInvalidException('Token not provided', Response::HTTP_NOT_FOUND);
            }

            if (! $this->auth->parseToken()->authenticate()) {
                throw new UserNotDefinedException();
            }
        } catch (TokenBlacklistedException  $e) {
            return response()->jsonException($e, ['status' => Response::HTTP_UNAUTHORIZED]);
        } catch (TokenExpiredException  $e) {
            return response()->jsonException($e, ['status' => Response::HTTP_UNAUTHORIZED]);
        } catch (TokenInvalidException  $e) {
            return response()->jsonException($e, ['status' => Response::HTTP_UNPROCESSABLE_ENTITY]);
        } catch (UserNotDefinedException  $e) {
            return response()->jsonNotFound(['message' => 'Invalid User for Token']);
        } catch (JWTException  $e) {
            return response()->jsonException($e, ['message' => 'JWT Exception Error']);
        } catch (Exception $e) {
            return response()->jsonException($e, ['message' => 'JWT Auth Validation Error']);
        }

        return $next($request);
    }
}
