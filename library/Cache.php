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
 * @package    HotelClub_Cache
 * @subpackage PHP
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 * @version    SVN: $Id$
 */

/**
 * HotelClub Cache class
 *
 * @category   HotelClub
 * @package    HotelClub_Cache
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 */
class HotelClub_Cache
{
    /**
     * Cache Directory.
     *
     * @var string
     */
    protected $_dir = null;

    /**
     * Cache File.
     *
     * @var string
     */
    protected $_file = null;

    /**
     * Service.
     *
     * @var HotelClub_Availability|HotelClub_Content|HotelClub_Reservation
     */
    protected $_service = null;

    /**
     * Time-To-Live.
     *
     * @var int
     */
    protected $_ttl = 86400;

    /**
     * Cache Disabled.
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * Constructor
     *
     * @param  @param HotelClub_Availability|HotelClub_Content|HotelClub_Reservation $service
     * @return void
     */
    public function __construct($service = null)
    {
        $this->_dir = (string) dirname(dirname(__FILE__)) . '/cache';
        if (!is_null($service)) {
            $this->_service = $service;
            $methodName = ucfirst($service->__get('_methodName'));
            $arguments = $service->__get('_arguments');
            $this->_file = (string) $this->_dir . '/' . sha1($methodName . serialize($arguments));
            if (isset($service->Config->cache['disable'])) {
                $disabled = $service->Config->cache['disable'];
                if (is_bool($disabled)) {
                    $this->disabled = (bool) $disabled;
                }
            }
            if (isset($service->Config->cache['ttl']['default'])) {
                $ttl = $service->Config->cache['ttl']['default'];
                if (is_numeric($ttl)) {
                    $this->_ttl = (int) $ttl;
                }
            }
            if (isset($service->Config->cache['ttl'][$methodName])) {
                $ttl = $service->Config->cache['ttl'][$methodName];
                if (is_numeric($ttl)) {
                    $this->_ttl = (int) $ttl;
                }
            }
        }
    }

    /**
     * Check
     *
     * @return bool
     */
    public function check()
    {
        if (is_file($this->_file)) {
            $filemtime = filemtime($this->_file);
            if ((time() - $filemtime) < $this->_ttl) {
                return true;
            }
        }
        return false;
    }

    /**
     * Flush
     *
     * @return void
     */
    public function flush()
    {
        $files = scandir($this->_dir);
        foreach ($files as $file) {
            if (($file !== '.') && ($file !== '..')) {
                $filename = $this->_dir . '/' . $file;
                unlink($filename);
            }
        }
        return;
    }

    /**
     * Read
     *
     * @return mixed
     */
    public function read()
    {
        if (!$this->disabled) {
            return unserialize(file_get_contents($this->_file));
        }
        return null;
    }

    /**
     * Write
     *
     * @return void
     */
    public function write($data)
    {
        if (!$this->disabled) {
            file_put_contents($this->_file, serialize($data));
            chmod($this->_file, 0777);
        }
        return;
    }
}