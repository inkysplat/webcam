<?php
/**
 * Converts strings from one character
 * encoding into another.
 *
 * Relies on:
 * @uses mb_string
 * @uses iconv
 */
class Charset
{
    /**
     * Destined Charset
     *
     * @access string
     * @var string
     */
    private $charset;

    /**
     * List of encodings
     *
     * @access private
     * @var array
     */
    private $encodings = array();

    /**
     * Constructor
     *
     * You can set the character set
     * in this method's constructor.
     *
     * @param string $charset
     * @throws Exception
     * @return void
     */
    public function __construct($charset = '')
    {
        if (!function_exists('mb_detect_order'))
        {
            throw new Exception(__METHOD__ . " mb_string Module Not Found");
        }

        if (!function_exists('iconv'))
        {
            throw new Exception(__METHOD__ . " iconv Module Not Found");
        }

        $this->listCharset();

        if ($charset != '')
        {
            $this->setCharset($charset);
        }
    }

    /**
     * Lists out all the charsets
     * based on a list given to it by mb_encode().
     *
     * @access private
     * @return array
     */
    private function listCharset()
    {

        for ($i = 1; $i < 16; $i++)
        {
            $this->encodings[] = 'ISO-8859-' . $i;
        }

        if (function_exists('mb_list_encodings'))
        {
            $this->encodings = array_merge($this->encodings, mb_list_encodings());
        }

        $this->encodings = array_unique($this->encodings);

        return $this->encodings;
    }

    /**
     * Set the destination charset.
     *
     * @access public
     * @param string $charset
     * @return boolean
     * @throws Exception
     */
    public function setCharset($charset)
    {

        if (!in_array($charset, $this->encodings))
        {
            throw new Exception(__METHOD__ . " Invalid Character Set");
        }

        $this->charset = $charset;

        try
        {
            mb_internal_encoding($this->charset);

            iconv_set_encoding('input_encoding', $this->charset);
            iconv_set_encoding('output_encoding', $this->charset);
            iconv_set_encoding('internal_encoding', $this->charset);

            return true;
        }
        catch (exception $e)
        {
            throw new Exception(__METHOD__ . " Error Setting Internal Encoding: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Detects the character encoding
     * so it knows what to convert from.
     *
     * @access private
     * @param string $unknown
     * @return boolean
     */
    private function detect($unknown)
    {

        //detect using ICONV
        foreach ($this->encodings as $encoding)
        {
            if (!isset($iconv_detected))
            {
                $possible       = iconv($encoding, $encoding, $unknown);
                if ($possible === $unknown)
                    $iconv_detected = $encoding;
            }
        }

        //detect using MB_STRING
        $mb_detected = mb_detect_encoding($unknown);

        //both reliably matched the characeter encoding
        if (($mb_detected === $iconv_detected) && $mb_detected !== FALSE)
        {
            $detected = $mb_detected;
        }

        //MB_STRING detected it but ICONV failed
        if (!isset($iconv_detected) && !$mb_detected)
        {
            $detected = $mb_detected;
        }

        //ICONV detected it but MB_STRING failed
        if (!$mb_detected && isset($iconv_detected))
        {
            $detected = $iconv_detected;
        }

        if (!empty($detected))
        {
            return $detected;
        }

        return false;
    }

    /**
     * Converts a string from one charset
     * to the destination.
     *
     * @access public
     * @param string $dirty
     * @return string
     * @throws Exception
     */
    public function encode($dirty)
    {

        if (count($this->encodings) == 0)
        {
            throw new Exception(__METHOD__ . " Missing Character Encoding List");
        }

        if (empty($this->charset))
        {
            throw new Exception(__METHOD__ . " Missing Target Character Set");
        }

        $detected = $this->detect($dirty);

        if ($clean = iconv($detected, $this->charset, $dirty))
        {
            return $clean;
        }

        if ($clean = mb_convert_encoding($dirty, $this->charset))
        {
            return $clean;
        }

        return $dirty;
    }
}
