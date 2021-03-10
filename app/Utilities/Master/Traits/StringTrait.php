<?php

namespace Apithy\Utilities\Master\Traits;

trait StringTrait
{
    /**
     * Sanitize variables (prevent XSS)
     *
     * @param string $data - Insecure data
     * @return string
     */
    public static function sanitize($data)
    {
        if ($data !== '' && $data !== null) {
            $data = self::trimmed($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            return $data;
        }
        return '';
    }

    /**
     * Removes all white spaces except one
     *
     * Example: foo    bar = foo bar
     *
     * @param string $data
     * @param boolean $save - If the function cant return null value
     * @return string|null
     */
    public static function trimmed($data, $save = false)
    {
        if ($data !== '' && $data !== null) {
            return trim(
                preg_replace_callback(
                    '/(\s\s+)/u',
                    function ($data) {
                        return ' ';
                    },
                    $data
                )
            );
        }
        return (($save) ? null : '');
    }

    /**
     * Especial para Taxonomy
     * Remueve espacios de más,
     * y convierte la primera letra en mayus.
     * Todas las demas quedan en minusculas.
     *
     * @param $string
     * @return string
     */
    public static function taxonomyString($string)
    {
        return self::trimmed(self::mbUcFirst($string), true);
    }

    /**
     * Convierte la primera letra en mayusculas,
     * todas las demas quedan en minusculas
     *
     * @param $str
     * @param string $encoding
     * @param bool $lower_str_end
     * @return string
     */
    public static function mbUcFirst($str, $encoding = 'UTF-8', $lower_str_end = true)
    {
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        $str_end = '';
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        } else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = "{$first_letter}{$str_end}";
        return $str;
    }
}
