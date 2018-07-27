<?php

namespace p4scu41\BaseCRUDApi\Support;

/**
 * String support helper functions
 *
 * @package App\Support
 *  @author  Pascual Pérez <pasperezn@gmail.com>
 *
 * @method static string trim(string $value)
 * @method static string stripTags(string $value)
 * @method static string sqlReplaceBindings(string $sql, array $bindings)
 * @method static string removeAccents(string $value)
 */
class StringSupport
{
    /**
     * Elimina espacios al inicio y final de la cadena,
     * elimina dobles espacios o más dentro de la cadena
     *
     * @param string $value Valor a eliminar los espacios al inicio y final
     *
     * @return string
     */
    public static function trim($value)
    {
        return preg_replace("'\s+'", ' ', trim($value));
    }

    /**
     * Elimina espacios al inicio y final de la cadena,
     * elimina dobles espacios o más dentro de la cadena y
     * elimina etiquetas HTML y PHP
     *
     * @param string $value Cadena a hacer procesar
     *
     * @return string
     */
    public static function stripTags($value)
    {
        return strip_tags(static::trim($value));
    }

    /**
     * Replace the placeholder ? in the sql statement with the value of bindings
     *
     * @param string $sql      Query SQL
     * @param array  $bindings Values associated with the query
     *
     * @return string
     */
    public static function sqlReplaceBindings($sql, $bindings)
    {
        $query = $sql;
        $placeholders = [];
        $replacements = [];

        if (count($bindings)) {
            foreach ($bindings as $value) {
                $placeholders[] = '/\?/';
                $replacements[] = is_string($value) ? '\'' . $value . '\'' :
                    (is_bool($value) ? json_encode($value) : $value); // Add quote to the binding
            }

            // Replace every placeholder with the binding
            $query = preg_replace($placeholders, $replacements, $query, 1);
        }

        return $query;
    }

    /**
     * Remove Accents
     *
     * @param string $string String to proccess
     *
     * @return string
     */
    public static function removeAccents($string)
    {
        $locale_backup = locale_get_default();
        setlocale(LC_ALL, 'en_US.utf8');

        $result = @iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        // $result = @iconv('UTF-8', 'ASCII//TRANSLIT', utf8_encode($string));
        // $result = @iconv('UTF-8', 'ASCII//TRANSLIT', mb_convert_encoding($string, 'UTF-8'));
        // $result = @iconv('ISO-8859-1', 'ASCII//TRANSLIT', $string);
        // $result = @iconv('UTF-8', mb_convert_encoding($string, "UTF-8", mb_detect_encoding($value, "UTF-8, ISO-8859-1, ISO-8859-15", true)));

        $result = str_replace('\'', '', $result);

        setlocale(LC_ALL, $locale_backup);

        return $result;
    }

    /**
     * Validate email with filter_var and checkdnsrr
     *
     * @param string $email
     * @return bool|string true if the email is valid or error message in any other case
     */
    public static function isValidEmail(string $email)
    {
        // First the general syntax of the string is checked with filter_var
        if (filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) {
            return 'format invalid';
        }

        // Then the domain substring (after the '@') is checked using the 'checkdnsrr' function
        $email_explode = explode('@', $email);
        $domain = array_pop($email_explode);
        // Add a dot to the end of the host name to make a fully qualified domain name
        $domain = trim($domain) . '.';

        if (!checkdnsrr($domain)) {
            return 'domain invalid';
        }

        // Fetch DNS Resource Records associated with a hostname
        // print_r((dns_get_record($domain));
        // print_r((getmxrr($domain, $mxhosts, $weight));
        // print_r(($mxhosts);
        // print_r(($weight);

        return true;
    }
}
