<?php
/**
 * HotelClub
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.txt. It is also available through the
 * world-wide-web at this URL:
 * http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@hotelclub.com so
 * we can send you a copy immediately.
 *
 * @category   HotelClub
 * @package    HotelClub_Abstract
 * @subpackage PHP
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 * @version    SVN: $Id$
 */

/**
 * @see HotelClub_Cache
 */
require_once 'Cache.php';

/**
 * @see HotelClub_Config
 */
require_once 'Config.php';

/**
 * HotelClub Abstract class
 *
 * The HotelClub_Abstract class is an abstract class representing HotelClub XML
 * Web Services.
 *
 * @category   HotelClub
 * @package    HotelClub_Abstract
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 */
abstract class HotelClub_Abstract
{
    /**
     * Version.
     */
    const VERSION = 2.0;

    /**
     * XMLNS URL.
     */
    const XMLNS = 'https://xml.hotelclub.net/xmlws/services/v2/';

    /**
     * Arguments.
     *
     * @var array
     */
    protected $_arguments = array();

    /**
     * Method Name.
     *
     * @var string
     */
    protected $_methodName = null;

    /**
     * WSDL URL.
     *
     * @var string
     */
    protected $_wsdl = null;

    /**
     * Config.
     *
     * @var HotelClub_Config
     */
    public $Config = null;

    /**
     * Call
     *
     * @param  string $name The name of the method being called.
     * @param  string $arguments The arguments to pass to the method.
     * @return mixed
     */
    public function __call($methodName, $arguments)
    {
        $arguments[0]['Version'] = self::VERSION;
        $this->_arguments = $arguments;
        $this->_methodName = ucfirst($methodName);
        $cache = new HotelClub_Cache($this);
        if (!$cache->disabled) {
            if ($cache->check()) {
                return $cache->read();
            }
        }
        try {
            $soapClient = $this->_getSoapClient();
            $soapHeader = $this->_getSoapHeader();
            $response = $soapClient->__soapCall($this->_methodName, $this->_arguments, null, $soapHeader);
            if (!$cache->disabled) {
                $cache->write($response);
            }
            return $response;
        }
        catch (Exception $e) {
            echo $e->faultstring;
            return false;
        }
    }

    /**
     * Constructor
     *
     * @param  string $wsdl The full WSDL URI of the web service being called.
     * @return void
     */
    public function __construct($wsdl = null)
    {
        $this->_wsdl = (string) $wsdl;
        $this->Config = new HotelClub_Config();
    }

    /**
     * Get
     *
     * @param  string $name The name of the property to get.
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Set
     *
     * @param  string $name The name of the property to set.
     * @param  mixed $value The value to set for $name.
     * @return void
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * Get Soap Client
     *
     * @return SoapHeader
     */
    protected function _getSoapClient()
    {
        return new SoapClient($this->_wsdl, array('uri' => self::XMLNS));
    }

    /**
     * Get Soap Header
     *
     * @return SoapHeader
     */
    protected function _getSoapHeader()
    {
        $clientIp = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $clientIp = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (strlen($_SERVER['REMOTE_ADDR']) > 7) {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }
        $header['AffiliateID'] = new SoapVar($this->Config->affiliate['id'], XSD_INT, null, null, null, self::XMLNS);
        $header['Password'] = new SoapVar($this->Config->affiliate['password'], XSD_STRING, null, null, null, self::XMLNS);
        if (!is_null($clientIp)) {
            $header['ClientIP'] = new SoapVar($clientIp, XSD_STRING, null, null, null, self::XMLNS);
        }
        return new SoapHeader(self::XMLNS, 'AuthenticationInfo', new SoapVar($header, SOAP_ENC_OBJECT));
    }
}