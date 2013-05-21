<?php

/**
 * @category   mailer
 * @package    Firestorm
 * @author     Adam Nicholls <adamnicholls1987@gmail.com>
 * @name       Mail
 * @since      01/05/2012
 * @subpackage library
 * @copyright  2012
 */
class Mail
{

    /**
     * Stores the 'to' address
     *
     * @access private
     * @var string
     */
    private $to = '';

    /**
     * Stores the 'reply-to' address
     *
     * @access private
     * @var string
     */
    private $replyto = '';

    /**
     * Stores the 'from' address
     *
     * @access private
     * @var string
     */
    private $from = '';

    /**
     * Stores the 'bcc' address
     *
     * @access private
     * @var string
     */
    private $bcc = '';

    /**
     * Stores the 'subject' line
     *
     * @access private
     * @var string
     */
    private $subject = '';

    /**
     * Stores the 'message' body content
     *
     * @access private
     * @var string
     */
    private $message = '';

    /**
     * HTML Email Flag
     *
     * @access private
     * @var boolean
     */
    private $html = false;

    /**
     * Additional Headers
     *
     * @access private
     * @var array
     */
    private $headers = array();

    /**
     * Email Directory
     * @access private
     * @var type
     */
    private $email_dir = null;

    /**
     * Public Contructor for Mail class
     *
     * @access public
     * @return null
     * @throws exception
     */
    public function __construct()
    {
	if (!function_exists('mail'))
	{
	    throw new exception(__METHOD__ . "::Missing Mail() Function");
	}

	if($this->email_dir == null)
	{
	    if (defined('EMAIL_PATH'))
	    {
		$this->email_dir = EMAIL_PATH;
	    } else
	    {
		$this->email_dir = SITE_PATH . '/tmp/';
	    }
	}
    }

    /**
     * Sets the Email parameters and executes any relevant code
     *
     * @magic
     * @uses ReflectionObject
     * @access public
     * @param string $name
     * @param string $value
     * @return null
     */
    public function __set($name, $value)
    {
	$name = strtolower($name);

	$reflect = new ReflectionObject($this);
	if ($reflect->hasProperty($name))
	{
	    $method_name = '_set' . ucfirst($name);
	    if (method_exists($this, $method_name))
	    {
		try
		{
		    $this->$method_name($value);
		}catch(exception $e)
		{
		    throw new exception($e->getMessage());
		}
	    }
	    $this->{$name} = $value;
	}
    }

    /**
     * Validates an Email Address
     *
     * @access public
     * @param string  $email
     * @return boolean
     */
    public static function isEmail($email)
    {
	return preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z.]{2,5}$/", $email);
    }

    /**
     * Sets a 'to' address - creates 'to' email header
     *
     * @access private
     * @param string    $to
     * @throws exception
     */
    private function _setTo($to)
    {
	if (empty($to))
	{
	    throw new exception(__METHOD__ . "::Missing 'To' Address");
	}

	if (!self::isEmail($to))
	{
	    throw new exception(__METHOD__ . "::Invalid 'To' Address");
	}

	if (!empty($this->to))
	{
	    throw new exception(__METHOD__ . "::Can only send to 1 person, CC others");
	}

	$this->headers[] = "To: " . $to . "";
    }

    /**
     * Sets a 'from' address - sets 'return-path' & 'from' headers
     *
     * @access private
     * @param string    $from
     * @throws exception
     */
    private function _setFrom($from)
    {
	if (empty($from))
	{
	    throw new exception(__METHOD__ . "::Missing 'From' Address");
	}

	if (!self::isEmail($from))
	{
	    throw new exception(__METHOD__ . "::Invalid 'From' Address");
	}

	if(!empty($this->from))
	{
	    throw new exception(__METHOD__ . "::Can only send from 1 person");
	}

	$name = $this->_extractName($from);
	$this->headers[] = "Return-Path: " . $name . " <" . $from . ">";
	$this->headers[] = "From: " . $name . " <" . $from . ">";
    }

    /**
     * Sets a 'reply-to' address - sets 'reply-to' email headers
     *
     * @access private
     * @param string    $replyto
     * @throws exception
     */
    private function _setReplyto($replyto)
    {
	if (empty($replyto))
	{
	    throw new exception(__METHOD__ . "::Missing 'Reply-to' Address");
	}

	if (!self::isEmail($replyto))
	{
	    throw new exception(__METHOD__ . "::Invalid 'Reply-to' Address");
	}
	if(!empty($this->replyto))
	{
	    throw new exception(__METHOD__ . "::Can only reply from 1 person");
	}

	$name = $this->_extractName($replyto);
	$this->headers[] = "Reply-To: " . $name . " <" . $replyto . ">";
    }

    /**
     * Sets a 'bcc' address - sets 'bcc' address in email headers
     *
     * @access private
     * @param string    $bcc
     * @throws exception
     */
    private function _setBcc($bcc)
    {
	if (empty($bcc))
	{
	    throw new exception(__METHOD__ . "::Missing 'Bcc' Address");
	}

	if (!self::isEmail($bcc))
	{
	    throw new exception(__METHOD__ . "::Invalid 'Bcc' Address");
	}

	$name = $this->_extractName($bcc);
	$this->headers[] = "Bcc: " . $name . " <" . $bcc . "";
    }

    /**
     * Sets HTML Email on/off - set appropriate headers
     *
     * @access private
     * @param boolean   $html
     * @throws exception
     */
    private function _setHtml($html)
    {
	if (!is_bool($html))
	{
	    throw new exception(__METHOD__ . "::Expecting HTML Flag As Boolean");
	}

	if ($html)
	{
	    $this->headers[] = "MIME-Version: 1.0";
	    $this->headers[] = "Content-Type: text/html; charset=utf-8";
	}
    }

    /**
     * Returns all the set headers
     *
     * @access private
     * @return string
     */
    private function getHeaders()
    {
	if (count($this->headers) > 0)
	{
	    $headers = implode("\r\n", $this->headers);
	}

	return $headers;
    }

    /**
     * Grabs everything before the @ symbol
     *
     * @access private
     * @param string  $email
     * @return string
     */
    private function _extractName($email)
    {
	$parts = explode('@', $email);
	return ucwords($parts[0]);
    }

    /**
     * Sends the email
     *
     * @access public
     * @return boolean
     * @throws exception
     */
    public function send()
    {

	if (empty($this->to))
	{
	    throw new exception(__METHOD__ . "::Missing To Address");
	}

	if (empty($this->from))
	{
	    throw new exception(__METHOD__ . "::Missing From Address");
	}

	if (empty($this->subject))
	{
	    throw new exception(__METHOD__ . "::Missing Subject");
	}

	if (empty($this->message))
	{
	    throw new exception(__METHOD__ . "::Missing Message");
	}

	$to = $this->to;
	$subject = $this->subject;
	$message = $this->message;
	$headers = $this->getHeaders();

	try
	{
	    $mail = mail($to, $subject, $message, $headers);

	    if ($mail)
	    {
		return true;
	    }
	} catch (exception $e)
	{
	    throw new exception(__METHOD__ . "::Unable to send::" . $e);
	}

	return false;
    }

    public function save()
    {
	if($this->email_dir == null)
	{
	    throw new exception(__METHOD__."::Missing Email Path Location");
	}

	if (empty($this->to))
	{
	    throw new exception(__METHOD__ . "::Missing To Address");
	}

	if (empty($this->from))
	{
	    throw new exception(__METHOD__ . "::Missing From Address");
	}

	if (empty($this->subject))
	{
	    throw new exception(__METHOD__ . "::Missing Subject");
	}

	if (empty($this->message))
	{
	    throw new exception(__METHOD__ . "::Missing Message");
	}

	if(!is_dir($this->email_dir))
	{
	    throw new exception(__METHOD__."::Missing Temporary Directory");
	}

	$date = date('Ymd-His');

	$filename = array(
	    $date,
	    $this->replyto,
	    md5($this->subject)
	);

	$filename = implode('-', $filename);
	$filename = $this->email_dir.$filename;

	$x = 0;

	$exists = $filename.'.msg';

	while(file_exists($exists))
	{
	    $exists = $filename.$x.'.msg';

	    $x++;
	}

	$message = <<<MESSAGE
To: {$this->to},
Reply-to: {$this->replyto},
From: {$this->from},
Subject: {$this->subject},
Date: {$date},
\n
Message:
\n------------------------
\n{$this->message}
MESSAGE;

	$filename = $exists;

	if($file = fopen($filename,'w+'))
	{
	    fwrite($file,$message);
	    fclose($file);

	    return true;
	}

	return false;
    }
}


