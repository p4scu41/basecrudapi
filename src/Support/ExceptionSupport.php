<?php

namespace p4scu41\BaseCRUDApi\Support;

use Auth;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Exception support helper functions
 *
 * @package p4scu41\BaseCRUDApi\Support
 * @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * @method static string function removeNoAppLines(Exception $e)
 * @method static string getInfo(Exception $e, boolean $include_trace = true, string $extra_data = '')
 * @method static string getMessageIfDebug(Exception $e, Monolog\Logger $logger = null, string $extra_data = '')
 * @method static boolean isDuplicatedKey(Exception $e)
 * @method static boolean isNotFound(Exception $e)
 * @method static boolean isForeignKeyNoExists(Exception $e)
 * @method static string getMessageIfNotQueryException(Exception $e)
 */
class ExceptionSupport
{
    const CODE_FORBIDDEN    = 403;
    const CODE_NOT_FOUND    = 404;
    const CODE_INVALID_DATA = 422;

    /**
     * Remove all lines no reference to App from getTraceAsString
     *
     * @param Exception $e Exception Instance
     *
     * @return string
     */
    public static function removeNoAppLinesFromTrace(Exception $e)
    {
        $lines = explode(PHP_EOL, $e->getTraceAsString());
        $info  = '';

        // On windows
        if (empty($lines) || count($lines) == 1) {
            $lines = explode("\n", $e->getTraceAsString());
        }

        if (!empty($lines)) {


            // Only get lines from App
            // $filtered = preg_grep('/APP/i', $lines);

            // Only get lines that not contains vendor/laravel
            $filtered = preg_grep('/vendor\/laravel/i', $lines, PREG_GREP_INVERT);

            if (!empty($filtered)) {
                $info = implode(PHP_EOL, $filtered);
            }
        }

        return $info;
    }

    /**
     * Get information as string of the Exception
     *
     * @param Exception $e             Exception Instance
     * @param boolean   $include_trace Wheter or not include Trace. Default true
     * @param string    $extra_data    Extra information useful
     *
     * @return string
     */
    public static function getInfo(Exception $e, $include_trace = true, $extra_data = '')
    {
        $file = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $e->getFile()) . ':' . $e->getLine();
        $info = /*PHP_EOL . "\t" .*/
            'Request: ' . request()->method() . ' ' .  request()->fullUrl() . PHP_EOL . "\t" .
            'IP: '      . request()->ip() . PHP_EOL . "\t" .
            // 'Headers: ' . json_encode(request()->header()) . PHP_EOL . "\t" .
            // 'Server: '  . json_encode(request()->server()) . PHP_EOL . "\t" .
            'File: '    . $file . PHP_EOL . "\t" .
            (Auth::check() ? 'Session User ID: ' . Auth::user()->id . PHP_EOL . "\t" : '' ) .
            'php_sapi_name: ' . php_sapi_name() . PHP_EOL .
            'user_process: ' . (function_exists('posix_getpwuid') ?
                    (posix_getpwuid(posix_geteuid())['name'] ) : // Linux
                    getenv('USERNAME')) . PHP_EOL . // Windows
            'Exception: ' . get_class($e) . '['.$e->getCode().']: ' . $e->getMessage() .
            (count(request()->all()) ? PHP_EOL . "\t" . 'Data: ' . json_encode(request()->all()) : '').
            (!empty($extra_data) ? PHP_EOL . "\t" . 'Extra Data: ' . $extra_data : '').
            ($include_trace ? PHP_EOL . static::removeNoAppLinesFromTrace($e) : '');
            //$e->getTraceAsString()

        return $info;
    }

    /**
     * Return the message of the exception if app.debug is true
     *
     * @param Exception      $e          Instancia de Exception
     * @param Monolog\Logger $logger     Logger Instance
     * @param string         $extra_data Extra information useful
     *
     * @return string
     */
    public static function getMessageIfDebug(Exception $e, $logger = null, $extra_data = '')
    {
        $info = static::getInfo($e, true, $extra_data);

        if (!empty($logger)) {
            $logger->error($info);
        } else {
            Log::error($info);
        }

        if (config('app.debug')) {
            return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $e->getFile()) . ':' . $e->getLine() .
                '. Code ' . $e->getCode() . '. ' .$e->getMessage();
        }

        /**
         * Error Code
         * 422 = Validación
         * 404 = No encontrado
         * 403 = Acceso denegado
         */
        if ($e->getCode() == 422 || $e->getCode() == 404 || $e->getCode() == 403) {
            return $e->getMessage();
        }

        return '';
    }

    /**
     * Check if the Exception Thrown is by duplicaded key
     *
     * @param Exception $e Exception trhown
     *
     * @return boolean
     */
    public static function isDuplicatedKey(Exception $e)
    {
        // PostgreSQL => SQLSTATE[23505]: Unique violation: 7 ERROR:  llave duplicada viola restricción de unicidad
        return $e->getCode() == 23505;
    }

    /**
     * Check if the code of the exception is 404
     *
     * @param Exception $e Exception Instance
     *
     * @return boolean
     */
    public static function isNotFound(Exception $e)
    {
        return $e->getCode() == 404;
    }

    /**
     * Check if the Exception Thrown is by foreign key not exists
     *
     * @param Exception $e Exception thrown
     *
     * @return boolean
     */
    public static function isForeignKeyNoExists(Exception $e)
    {
        // PostgreSQL => SQLSTATE[23503]: Foreign key violation 7 ERROR:  inserción o actualización
        // en la tabla A viola la llave foránea FK
        return $e->getCode() == 23503;
    }

    /**
     * Check if the Exception is instance of Illuminate\Database\QueryException
     * if it is so return an alternative message
     *
     * @param Exception $e Exception thrown
     *
     * @return string
     */
    public static function getMessageIfNotQueryException(Exception $e)
    {
        return ($e instanceof \Illuminate\Database\QueryException) ? 'Error al procesar los datos' :
            (!empty($e->getMessage()) ? $e->getMessage() : 'Error al procesar los datos');
    }
}
