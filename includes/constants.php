<?php

/*
 * this file is part of an ecommerce website developed as a TAFE project
 * Copyright Daniel Jones (daniel@danieljon.es) 2019
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * leftshifting to toggle bits to use as flags
 * PHP integers _can_ be 64-bit but it is platform dependent,
 * just assume 32-bit and have a maximum of 32 flags!
 */
define("BADCAPTCHA", 1<<0);
define("BADFIRSTNAME", 1<<1);
define("BADLASTNAME", 1<<2);
define("BADEMAIL", 1<<3);
define("BADPHONE", 1<<4);
define("BADPASSWORD", 1<<5);
define("BADREENTERPASSWORD", 1<<6);
define("PASSWORDMISMATCH", 1<<7);
define("UNKNOWNUSER", 1<<8);
define("DBERROR", 1<<9);
define("EMAILTAKEN", 1<<10);
define("LOGINNOW", 1<<11);
define("BADACTIVATIONCODE", 1<<12);
define("NEWCODENOTALLOWED", 1<<13);
define("CODECHANGED", 1<<14);
define("UNKNOWNITEM", 1<<15);
define("CHECKOUTLOGIN", 1<<16);
define("BADADDRESS", 1<<17);
define("BADTOWN", 1<<18);
define("BADPOSTCODE", 1<<19);
define("BADCARDNUMBER", 1<<20);
define("BADCARDNAME", 1<<21);
define("BADCVV", 1<<22);
define("ORDERCOMPLETE", 1<<23);
?>
