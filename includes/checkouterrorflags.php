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

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/constants.php");

function showerrors($flag)
{
	$errorstr = "";
	if ($flag & DBERROR) $errorstr .= "Database error.<br>";
	if ($flag & BADFIRSTNAME) $errorstr .= "The name field cannot be empty.<br>";
	if ($flag & BADADDRESS) $errorstr .= "The address field cannot be empty.<br>";
	if ($flag & BADTOWN) $errorstr .= "The town/city field cannot be empty.<br>";
	if ($flag & BADPOSTCODE) $errorstr .= "The postcode is invalid.<br>";
	if ($flag & BADCARDNUMBER) $errorstr .= "The card number is invalid. It must be 16 or more characters.<br>";
	if ($flag & BADCARDNAME) $errorstr .= "The card name field cannot be empty.<br>";
	if ($flag & BADCVV) $errorstr .= "The card CVV is invalid. It must be 3 digits.<br>";
	echo $errorstr . "<hr>";
}

if (isset($_GET['error']))
{
	$flag =  $_GET["error"];
	showerrors($flag);
}
?>
