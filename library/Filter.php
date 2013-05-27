<?php

/**
 * Filters characters from string based
 * on pre-defined regular expressions
 * which whitelist those characers
 * expected.
 *
 */
class Filter
{
    /**
     * Regular Expression
     * Constant for Decimal Characters
     *
     * @const
     */

    const DECIMAL = "/[^0-9\.]/";

    /**
     * Regular Expression
     * Constant for Number Characters
     *
     * @const
     */

    const NUMBER = "/[^0-9]/";

    /**
     * Regular Expression
     * Constant for Alphabetic Characters
     *
     * @const
     */

    const ALPHA = "/[^A-Za-z\w]/";

    /**
     * Regular Expression
     * Constant for Alphanumeric Characters
     *
     * @const
     */

    const ALPHANUM = "/[^A-Za-z0-9\w]/";

    /**
     * Regular Expression
     * Constant for Hexadecimal Characters
     *
     * @const
     */

    const HEX = "/[^0-9A-F]/";

    /**
     * Constructor
     *
     * You can pass your dirty string
     * and filter into this constructor.
     *
     * @param string $dirty
     * @param string $method
     * @return string
     * @throws Exception
     */
    public function __construct($dirty, $method)
    {
        if (!method_exists($this, $method))
        {
            throw new Exception(__METHOD__ . " No Such Method");
        }

        return self::$method($dirty);
    }

    /**
     * Converts string to decimal
     *
     * @static
     * @param string $dirty
     * @return string
     */
    public static function decimal($dirty)
    {
        return self::clean($dirty, self::DECIMAL);
    }

    /**
     * Converts string to number
     *
     * @static
     * @param string $dirty
     * @return string
     */
    public static function number($dirty)
    {
        return self::clean($dirty, self::NUMBER);
    }

    /**
     * Converts string to alphabetic
     *
     * @static
     * @param string $dirty
     * @return string
     */
    public static function alpha($dirty)
    {
        return self::clean($dirty, self::ALPHA);
    }

    /**
     * Converts string to alphanumeric
     *
     * @static
     * @param string $dirty
     * @return string
     */
    public static function alphanum($dirty)
    {
        return self::clean($dirty, self::ALPHANUM);
    }

    /**
     * Converts string to hex
     *
     * @static
     * @param string $dirty
     * @return string
     */
    public static function hex($dirty)
    {
        return self::clean($dirty, self::HEX);
    }

    /**
     * Cleans the string using preg_replace.
     *
     * @static
     * @access private
     * @param string $dirty
     * @return string
     */
    private static function clean($dirty, $pattern)
    {
        return trim(preg_replace($pattern, '', $dirty));
    }

    /**
     * Trims the string.
     *
     * @static
     * @param string $dirty
     * @param int $length
     * @param char position
     * @return string
     */
    public static function trim($dirty, $length, $position = 'L')
    {

        switch ($position)
        {
            case 'R':
                $length  = (int) '-' . $length;
                $trimmed = substr($dirty, $length);
                break;
            case 'L':
                $trimmed = substr($dirty, 0, $length);
                break;
        }

        return $trimmed;
    }

    /**
     * URL Decode a string
     *
     * @static
     * @param string $dirty
     * @return string
     */
    public static function urldecode($dirty)
    {
        return urldecode($dirty);
    }

    /**
     * URL Encode a string
     *
     * @static
     * @param string $dirty
     * @return string
     */
    public static function urlencode($dirty)
    {
        return urlencode($dirty);
    }

}

?>
