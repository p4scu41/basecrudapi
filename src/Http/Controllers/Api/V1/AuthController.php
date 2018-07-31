<?php

namespace p4scu41\BaseCRUDApi\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use p4scu41\BaseCRUDApi\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Validator;
use p4scu41\BaseCRUDApi\Support\ArraySupport;

/**
 * Auth Controller Class
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-07-30
 */
class AuthController extends Controller
{
    /**
     * Login
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Grab credentials from the request
        $credentials = $request->only(['email', 'password']);
        // Login only available for active users
        $credentials['active'] = 1;
        $data = [
            'token'      => null,
            'id'         => null,
            'type'       => 'bearer',
            'expires_in' => null,
        ];

        try {
            $this->_validateInputs($credentials);

            // Create token that lives forever
            if ($request->input('durable')) {
                // \Config::set('jwt.ttl', null);
                config('jwt.ttl', null);
                // config('jwt.refresh_ttl', null);
                config('jwt.blacklist_enabled', null);
                config('jwt.required_claims', ['iss', 'iat', 'nbf', 'sub', 'jti']);
            }

            if (! $token = auth()->setTTL(config('jwt.ttl'))->attempt($credentials)) {
                // Log login attempt
                $credentials['password'] = '';
                $request->merge(['password' => '']);
                \Log::warning('Login attempt', $credentials);

                return response()->jsonInvalidData(['message' => 'E-mail or Password invalid.']);
            }

            // Get the authenticated user
            $user = auth()->setToken($token)->user();
            // Store the datetime on last_login
            $user->last_login = date('Y-m-d H:i:s');
            $user->save();

            $data['token']      = $token;
            $data['id']         = $user->id;
            $data['expires_in'] = auth()->factory()->getTTL() * 60;
        } catch (Exception $e) {
            return response()->jsonException($e);
        }

        return response()->jsonSuccess(['data' => $data]);
    }

    /**
     * Validate data required to login
     *
     * @param array $inputs
     *
     * @throws Exception
     */
    private function _validateInputs($inputs)
    {
        $validator = Validator::make($inputs, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new Exception(
                ArraySupport::errorsToString($validator->errors()->all()),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Refresh token
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        $data = [
            'token'      => null,
            'id'         => null,
            'type'       => 'bearer',
            'expires_in' => null,
        ];

        try {
            if (! $token = auth()->refresh(true, true)) {
                // Log login attempt
                \Log::warning('Refresh token attempt', ['token' => $request->header('Authorization')]);

                return response()->jsonInvalidData(['message' => 'Unable to refresh token.']);
            }

            // Get the authenticated user
            $user = auth()->setToken($token)->user();
            // Store the datetime on last_login
            $user->last_login = date('Y-m-d H:i:s');
            $user->save();

            $data['token']      = $token;
            $data['id']         = $user->id;
            $data['expires_in'] = auth()->factory()->getTTL() * 60;
        } catch (Exception $e) {
            \Log::warning('Refresh token attempt', ['token' => $request->header('Authorization')]);

            return response()->jsonException($e);
        }

        return response()->jsonSuccess(['data' => $data]);
    }

    /**
     * Recovery Password
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function recoveryPassword(Request $request)
    {
        //
    }

    /**
     * Reset Password
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        //
    }

    /**
     * logout
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->logout();

        return response()->jsonSuccess(['message' => 'Logout successful']);
    }

    /**
     * Me
     *
     * @param \Illuminate\Http\Request $request Request instance
     *
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        return response()->jsonSuccess(['data' => $request->user()]);
    }
}
