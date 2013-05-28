<?php

/**
 * Description of Filter
 *
 * @author Adam
 */
class Validate
{

    public static function is_email($value)
    {
        return preg_match("^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$", $value);
    }

    public static function is_bool($value)
    {
        if (preg_match("/[0-1]{1}/", $value))
            return true;
        if ($value == true || $value == false)
            return true;
        return false;
    }

    public static function is_string($value)
    {
        if (strlen($value) >= 0 && $value != '' && @!is_array($value))
            return true;
        return false;
    }

    public static function is_decimal($value)
    {
        return preg_match("/[0-9\.]/", $value);
    }

    public static function is_hex($value)
    {
        return preg_match("/[A-F0-9]/", $value);
    }

    public static function is_array($value)
    {
        if (@count($value) <> 0 && is_array($value))
            return true;
        return false;
    }

}

?>
