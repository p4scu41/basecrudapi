<?php

namespace p4scu41\BaseCRUDApi\Support;

/**
 * Array support helper functions
 *
 * @package p4scu41\BaseCRUDApi\Support
 * @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * @method static array arrayFilterRecursive(array $array)
 * @method static string errorsToString(array $errors, string $join)
 * @method static array trim(array $data)
 * @method static array stripTags(array $data)
 * @method static array catalogSiNo(string $label, string $id)
 */
class ArraySupport
{
    /**
     * Implement array_filter recursive
     *
     * @param array $array Arreglo
     *
     * @return array
     */
    public static function arrayFilterRecursive($array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = static::array_filter_recursive($value);
            }
        }

        return array_filter($array);
    }

    /**
     * Convierte el array de los errores del modelo a una cadena de texto
     *
     * @param array  $errors Errores del modelo
     * @param string $join   Default .
     *
     * @return String
     */
    public static function errorsToString($errors, $join = " ")
    {
        return array_reduce((array) $errors, function ($result, $item) use ($join) {
            return $result . (
                empty($item) ? '' :
                (is_array($item) ? static::errorsToString($item) : ' '.StringSupport::trim($item) . $join)
            );
        });
    }

    /**
     * Elimina espacios al inicio y final de la cadena,
     * elimina dobles espacios o más dentro de la cadena
     * contenidas en un arreglo
     *
     * @param array $data Arreglo a eliminar los espacios al inicio y final
     *
     * @return array
     */
    public static function trim($data)
    {
        return collect($data)->map(function ($value, $key) {
            // Si el valor es una cadena
            if (is_string($value)) {
                // Elimina espacios al inicio y final de la cadena
                // Elimina dobles espacios o más dentro de la cadena
                return [$key => StringSupport::trim($value)];
            }

            return $value;
        })->toArray();
    }

    /**
     * Elimina espacios al inicio y final de la cadena,
     * elimina dobles espacios o más dentro de la cadena,
     * elimina etiquetas HTML y PHP
     * contenidos en un arreglo
     *
     * @param array $data Arreglo de elementos a procesar
     *
     * @return array
     */
    public static function stripTags($data)
    {
        return collect($data)->map(function ($value, $key) {
            // Si el valor es una cadena
            if (is_string($value)) {
                // Elimina espacios al inicio y final de la cadena
                // Elimina dobles espacios o más dentro de la cadena
                // Elimina etiquetas HTML y PHP
                return StringSupport::stripTags($value);
            }

            return $value;
        })->toArray();

        return $result;
    }

    /**
     * Return array Si, No
     *
     * @param string $label Label
     * @param string $id    ID
     *
     * @return array
     */
    public static function catalogSiNo($label = null, $id = '')
    {
        $options = [
            '1' => 'Si',
            '0' => 'No',
        ];

        if (!empty($label)) {
            $options = [$id => $label] + $options;
        }

        return $options;
    }
}
