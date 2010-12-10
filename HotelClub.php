<?php
/**
 * HotelClub
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE. It is also available through the world-wide-web
 * at this URL: http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@hotelclub.com so
 * we can send you a copy immediately.
 *
 * @category   HotelClub
 * @package    HotelClub
 * @subpackage PHP
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 * @version    SVN: $Id$
 */

/**
 * @see HotelClub_Availability
 */
require_once 'library/Availability.php';

/**
 * @see HotelClub_Content
 */
require_once 'library/Content.php';

/**
 * @see HotelClub_Reservation
 */
require_once 'library/Reservation.php';

/**
 * HotelClub class
 *
 * @category   HotelClub
 * @package    HotelClub
 * @copyright  Copyright (c) 2010 HotelClub Pty Ltd (http://www.hotelclub.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php    New BSD License
 */
class HotelClub
{
    /**
     * Availability.
     *
     * @var HotelClub_Availability
     */
    public $Availability = null;

    /**
     * Content.
     *
     * @var HotelClub_Content
     */
    public $Content = null;

    /**
     * Reservation.
     *
     * @var HotelClub_Reservation
     */
    public $Reservation = null;

    public function __construct()
    {
        $this->Availability = new HotelClub_Availability();
        $this->Content = new HotelClub_Content();
        $this->Reservation = new HotelClub_Reservation();
    }
}