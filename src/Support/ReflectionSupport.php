<?php

namespace p4scu41\BaseCRUDApi\Support;

/**
 * Reflection support helper functions
 *
 * @package p4scu41\BaseCRUDApi\Support
 *  @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * @method static boolean isNotSetOrEmpty(array $array, string $key)
 * @method static boolean isSetAndNotEmpty(array $array, string $key)
 * @method static boolean hasProperty(Object $obj, string $prop)
 */
class ReflectionSupport
{
    /**
     * Revisa si un elemento no esta definido o tiene un valor vacío
     *
     * @param array  $array Arreglo asociativo donde se buscará key
     * @param string $key   Llave a buscar en el arreglo
     *
     * @return boolean
     */
    public static function isNotSetOrEmpty($array, $key)
    {
        if (!is_array($array)) {
            return false;
        }

        if (!isset($array[$key])) {
            return true;
        } elseif (empty($array[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Revisa si un elemento esta definido y además no es vacío
     *
     * @param array  $array Arreglo asociativo donde se buscará key
     * @param string $key   Llave a buscar en el arreglo
     *
     * @return boolean
     */
    public static function isSetAndNotEmpty($array, $key)
    {
        if (!is_array($array)) {
            return false;
        }

        if (isset($array[$key])) {
            if (!empty($array[$key])) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Busca si existe una propiedad dentro del objeto
     *
     * @param Object $obj  El objeto
     * @param string $prop El nombre de la propiedad a buscar
     *
     * @return boolean
     */
    public static function hasProperty($obj, $prop)
    {
        $ar_properties[] = get_object_vars($obj);

        // $return array_key_exists($prop, get_object_vars($obj));
        // return property_exists($obj, $prop) && isset($obj->$prop);

        foreach ($ar_properties as $ar) {
            foreach ($ar as $k => $v) {
                if ($k == $prop) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Obtiene el nombre de la clase sin namespace
     *
     * @param Object $obj
     *
     * @return string
     */
    public static function getShortClassName($obj)
    {
        return (new \ReflectionClass($obj))->getShortName();
    }
}
