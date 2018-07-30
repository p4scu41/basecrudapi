<?php

namespace p4scu41\BaseCRUDApi\Support;

use Carbon\Carbon;

/**
 * Formatter support helper functions
 *
 * @package p4scu41\BaseCRUDApi\Support
 * @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * @method static string parseDate(string $date, string $formatOutput = 'd/m/Y h:i a', string $formatInput = 'Y-m-d H:i:s')
 * @method static integer GbMbToKb(string $val)
 * @method static string parseBytes(integer $bytes, integer $precision)
 * @method static string parseNumber(integer $cantidad)
 */
class FormatterSupport
{
    /**
     * Convierte una fecha del $formatInput al $formatOutput
     *
     * @param string $date         Fecha
     * @param string $formatOutput Formato de salida. Default d/m/Y h:i a
     * @param string $formatInput  Formato de entrada. Default Y-m-d H:i:s
     *
     * @return string
     */
    public static function parseDate($date, $formatOutput = 'd/m/Y h:i a', $formatInput = 'Y-m-d H:i:s')
    {
        if (empty($date)) {
            return '';
        }

        $date = Carbon::createFromFormat($formatInput, $date);

        if (empty($date)) {
            return '';
        }

        return $date->format($formatOutput);
    }

    /**
     * Obtiene la cantidad en kilobytes de una expresion en cadena,
     * ejemplo 2M = 2048
     *
     * @param string $val Valor a procesar
     *
     * @return int
     */
    public static function GbMbToKb($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);

        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
        }

        return $val;
    }

    /**
     * Convierte los bytes a la unidad correspondiente
     * from http://php.net/manual/en/function.filesize.php
     *
     * @param integer $bytes     Cantidad en bytes
     * @param integer $precision Default 2
     *
     * @return string
     */
    public static function parseBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . $units[$pow];
    }

    /**
     * Convierte la cantidad numérica a K (Miles) o M (Millones)
     *
     * @param integer $cantidad Cantidad a convertir
     *
     * @return string
     */
    public static function parseNumber($cantidad)
    {
        $formatted = $cantidad;

        if ($cantidad >= 1000000) {
            $formatted = number_format($cantidad/1000000, 0) . 'M';
        } elseif ($cantidad >= 1000) {
            $formatted = number_format($cantidad/1000, 0) . 'K';
        } else {
            $formatted = number_format($cantidad, 0);
        }

        return $formatted;
    }

    /**
     * Get SQL query from the Query Builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return string
     */
    public static function getSqlFromQueryBuilder($query)
    {
        return StringSupport::sqlReplaceBindings($query->toSql(), $query->getBindings());
    }

    /**
     * Parse microtime to human readable
     *
     * @param int $time
     * @param string $unit
     * @param integer $decimals
     *
     * @return strin
     */
    public static function timeToHuman($time)
    {
        $sign = $time < 0 ? "-" : "";

        $decimal = explode('.', $time);
        $ms   = abs($time);
        $sec  = floor($ms / 1000);
        $ms   = $ms % 1000 . ($decimal[1] ?: '.' . $decimal[1]);
        $min  = floor($sec / 60);
        $sec  = $sec % 60;
        $hr   = floor($min / 60);
        $min  = $min % 60;
        $day  = floor($hr / 60);
        $hr   = $hr % 60;

        return $sign .
            ($day >0 ? $day . 'd ' : '') .
            ($hr >0 ? $hr . 'h ' : '') .
            ($min >0 ? $min . 'm ' : '') .
            ($sec >0 ? $sec . 's ' : '') .
            ($ms >0 ? $ms . 'ms' : '')
        ;
    }

    /**
     * Parse bytes to human readable
     *
     * @param int $bytes
     * @param string $unit
     * @param integer $decimals
     *
     * @return strin
     */
    public static function memoryToHuman($bytes, $unit = "", $decimals = 2)
    {
        if ($bytes <= 0) {
            return '0.00 KB';
        }

        $units = [
            'B' => 0,
            'KB' => 1,
            'MB' => 2,
            'GB' => 3,
            'TB' => 4,
            'PB' => 5,
            'EB' => 6,
            'ZB' => 7,
            'YB' => 8
        ];

        $value = 0;
        if ($bytes > 0) {
            // Generate automatic prefix by bytes
            // If wrong prefix given
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes) / log(1024));
                $unit = array_search($pow, $units);
            }

            // Calculate byte value by prefix
            $value = ($bytes / pow(1024, floor($units[$unit])));
        }

        // If decimals is not numeric or decimals is less than 0
        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        // Format output
        return sprintf('%.' . $decimals . 'f ' . $unit, $value);
    }
}
