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
 * @package    HotelClub_Availability
 * @subpackage PHP
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 * @version    SVN: $Id$
 */

/**
 * @see HotelClub_Abstract
 */
require_once 'Abstract.php';

/**
 * HotelClub Availability class
 *
 * The HotelClub_Availability class is a concrete subclass of HotelClub_Abstract
 * meant for representing the HotelClub Availability Web Service.
 *
 * @category   HotelClub
 * @package    HotelClub_Availability
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 */
class HotelClub_Availability extends HotelClub_Abstract
{
    /**
     * WSDL URL.
     */
    const WSDL = 'https://xml.hotelclub.net/XMLWS_V2/XmlWsdl/V2.00/Availability.asmx?WSDL';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(self::WSDL);
    }
}