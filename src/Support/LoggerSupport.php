<?php

namespace p4scu41\BaseCRUDApi\Support;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Logger support helper functions
 *
 * @package p4scu41\BaseCRUDApi\Support
 * @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * @method static Monolog\Logger getInstance(string|object $file, string $sufix = '')
 */
class LoggerSupport
{
    /**
     * Crea una instancia de Logger para poder hacer debug a un archivo especifico
     *
     * @param string|object $file  Nombre del archivo donde se guarda el log o
     *                             instancia de la clase para la que se desea crear el logger
     *  @param string       $sufix File name sufix
     *
     * @return Monolog\Logger
     */
    public static function createLogger($file, $sufix = '')
    {
        if (!is_string($file)) { // Object
            $className = str_replace(['App\\', '\\'], ['', '_'], get_class($file));
            $file = $className.'_'.date('Y-m-d').$sufix.'.log';
        }

        $stream = new StreamHandler(storage_path() . DIRECTORY_SEPARATOR  . 'logs' . DIRECTORY_SEPARATOR . $file, Logger::INFO, true, 0777);
        $stream->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message% %context%\n", null, true));
        $log = new Logger('log');

        return $log->pushHandler($stream);
    }
}
