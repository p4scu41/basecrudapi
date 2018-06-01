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
}
