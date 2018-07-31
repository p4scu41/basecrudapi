<?php

namespace p4scu41\BaseCRUDApi\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use p4scu41\BaseCRUDApi\Support\ExceptionSupport;
use p4scu41\BaseCRUDApi\Support\ReflectionSupport;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Agrega metodos al reponse() para devolver respuestas en formato json
 * Métodos agregados:
 *     jsonFormat, jsonSuccess, jsonNotFound, jsonForbidden, jsonInvalidData, jsonException, jsonJwtException
 *
 * @package p4scu41\BaseCRUDApi\Providers
 * @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * @property array $code_http_response
 * @property array $json_format
 */
class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Formato de la respuesta json
     *
     * @var array
     */
    public static $json_format = [
        // Datos solicitados
        'data'    => [],
        // Mensaje breve descriptivo de la respuesta
        'message' => null,
        // Código de la respuesta HTTP
        'status'  => null,
        // Código del error
        'error'   => null,
        // Datos extras que se requieran
        'meta'   => null,
    ];

    /**
     * Return the response format with data content
     *
     * @param array $data    Format ResponseMacroServiceProvider::$json_format
     * @param int   $status  Http status code
     * @param array $headers Extra headers
     *
     * @return response()->json()
     */
    public static function responseJson($data, $status, $headers)
    {
        $content = array_merge(static::$json_format, (array) $data);

        if (empty($content['status'])) {
            $content['status'] = $status;
        }

        return response()->json($content, $status, $headers);
    }

    /**
     * Set the HTTP Status Code
     *
     * @param array $data  Format ResponseMacroServiceProvider::$json_format
     * @param int   $code  Http status code
     *
     * @return void
     */
    public static function setHttpStatusCode(&$data, $default_code)
    {
        if (ReflectionSupport::isNotSetOrEmpty($data, 'status')) {
            $data['status'] = $default_code;
        }

        $data['status'] = ReflectionSupport::isNotSetOrEmpty(SymfonyResponse::$statusTexts, $data['status']) ?
            $default_code : $data['status'];

        if (ReflectionSupport::isNotSetOrEmpty($data, 'message')) {
            $data['message'] = SymfonyResponse::$statusTexts[$data['status']];
        }

        if (ReflectionSupport::isNotSetOrEmpty($data, 'error') && $data['status'] >= SymfonyResponse::HTTP_MULTIPLE_CHOICES) {
            $data['error'] = $data['status'];
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Return the response format with data content
         *
         * @param array $data    Format ResponseMacroServiceProvider::$json_format
         * @param int   $status  Http status code
         * @param array $headers Extra headers
         *
         * @return ResponseMacroServiceProvider::responseJson()
         */
        Response::macro('jsonFormat', function ($data = [], $status = 200, $headers = []) {
            $http_status = ReflectionSupport::isNotSetOrEmpty(
                SymfonyResponse::$statusTexts,
                $status
            ) ? SymfonyResponse::HTTP_OK : $status;

            return ResponseMacroServiceProvider::responseJson($data, $http_status, $headers);
        });

        /**
         * Return the response format with data content
         *
         * @param array $data    Format ResponseMacroServiceProvider::$json_format
         * @param int   $status  200 OK
         * @param array $headers Extra headers
         *
         * @return ResponseMacroServiceProvider::responseJson()
         */
        Response::macro('jsonSuccess', function ($data = [], $status = 200, $headers = []) {
            ResponseMacroServiceProvider::setHttpStatusCode($data, $status);

            return ResponseMacroServiceProvider::responseJson($data, $data['status'], $headers);
        });

        /**
         * Return the response format with data content and http status code 404 Not Found
         *
         * @param array $data    Format ResponseMacroServiceProvider::$json_format
         * @param array $headers Extra headers
         *
         * @return ResponseMacroServiceProvider::responseJson()
         */
        Response::macro('jsonNotFound', function ($data = [], $headers = []) {
            ResponseMacroServiceProvider::setHttpStatusCode($data, SymfonyResponse::HTTP_NOT_FOUND);

            return ResponseMacroServiceProvider::responseJson($data, $data['status'], $headers);
        });

        /**
         * Return the response format with data content and http status code 403 Forbidden
         *
         * @param array $data    Format ResponseMacroServiceProvider::$json_format
         * @param array $headers Extra headers
         *
         * @return ResponseMacroServiceProvider::responseJson()
         */
        Response::macro('jsonForbidden', function ($data = [], $headers = []) {
            ResponseMacroServiceProvider::setHttpStatusCode($data, SymfonyResponse::HTTP_FORBIDDEN);

            return ResponseMacroServiceProvider::responseJson($data, $data['status'], $headers);
        });

        /**
         * Return the response format with data content and http status code 422
         * Data Validation Failed, Unprocessable Entity
         *
         * @param array $data    Format ResponseMacroServiceProvider::$json_format
         * @param array $headers Extra headers
         *
         * @return ResponseMacroServiceProvider::responseJson()
         */
        Response::macro('jsonInvalidData', function ($data = [], $headers = []) {
            ResponseMacroServiceProvider::setHttpStatusCode($data, SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);

            return ResponseMacroServiceProvider::responseJson($data, $data['status'], $headers);
        });

        /**
         * Return the response format with data content and the exception
         * If the $e->getCode() is a valid http status code the response is return
         * with this code, in oder hand, the http status code is set with 500
         *
         * @param \Exception $e       Exception
         * @param array      $data    Format ResponseMacroServiceProvider::$json_format
         * @param array      $headers Extra headers
         *
         * @return ResponseMacroServiceProvider::responseJson()
         */
        Response::macro('jsonException', function ($e, $data = [], $headers = []) {
            if (ReflectionSupport::isSetAndNotEmpty($data, 'status')) {
                $status = ReflectionSupport::isNotSetOrEmpty(SymfonyResponse::$statusTexts, $data['status']) ?
                    SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
                    : $data['status'];
            } else {
                $status = ReflectionSupport::isNotSetOrEmpty(SymfonyResponse::$statusTexts, $e->getCode()) ?
                    SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR
                    : $e->getCode();
            }

            if (ReflectionSupport::isNotSetOrEmpty($data, 'status')) {
                $data['status'] = $status;
            }

            if (ReflectionSupport::isNotSetOrEmpty($data, 'error')) {
                $data['error'] = $e->getCode();
            }

            if (ReflectionSupport::isNotSetOrEmpty($data, 'message')) {
                $data['message'] = SymfonyResponse::$statusTexts[$status] . '. ' .
                    ExceptionSupport::getMessageIfNotQueryException($e);
            }

            if (ReflectionSupport::isNotSetOrEmpty($data, 'meta') && config('app.debug')) {
                    $traceAsString = ExceptionSupport::removeNoAppLinesFromTrace($e);
                    $traceAsArray = explode(PHP_EOL, $e->getTraceAsString());

                    // On windows
                    if (empty($trace) || count($trace) == 1) {
                        $traceAsArray = explode("\n", $traceAsString);
                    }

                    $data['meta']['exception'] = get_class($e);
                    $data['meta']['error']     = $e->getCode();
                    $data['meta']['message']   = $e->getMessage();
                    $data['meta']['file']      = $e->getFile();
                    $data['meta']['line']      = $e->getLine();
                    $data['meta']['input']     = request()->all();
                    $data['meta']['trace']     = $traceAsArray;
            }

            Log::debug($data['message'], $data);

            // Check to see if LERN is installed otherwise you will not get an exception.
            if (app()->bound("lern")) {
                \Tylercd100\LERN\Facades\LERN::record($e);
            }

            return ResponseMacroServiceProvider::responseJson($data, $status, $headers);
        });

        /**
         * Return the response format with content of the paginator
         *
         * @param Illuminate\Pagination\LengthAwarePaginator $paginator
         * @param array $headers                              Extra headers
         *
         * @return ResponseMacroServiceProvider::responseJson()
         */
        Response::macro('jsonPaginate', function ($paginator, $headers = []) {
            $result = $paginator->toArray();

            return response()->jsonSuccess([
                'data' => $result['data'],
                'links' => [
                    'path'           => $result['path'],
                    'first_page_url' => $result['first_page_url'],
                    'last_page_url'  => $result['last_page_url'],
                    'next_page_url'  => $result['next_page_url'],
                    'prev_page_url'  => $result['prev_page_url'],
                ],
                'meta' => [
                    'per_page'     => $result['per_page'],
                    'current_page' => $result['current_page'],
                    'from'         => $result['from'],
                    'to'           => $result['to'],
                    'last_page'    => $result['last_page'],
                    'total'        => $result['total'],
                ]
            ], SymfonyResponse::HTTP_OK, $headers);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
