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

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/constants.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/userclass.php");

function showerrors($flag)
{
	$errorstr = "";
	if ($flag & DBERROR) $errorstr .= "Database error. Please try again later.<br>";
	if ($flag & BADACTIVATIONCODE) $errorstr .= "Activation code was incorrect.<br>";
	if ($flag & NEWCODENOTALLOWED) $errorstr .= "You are not allowed to change your code anymore.<br>";
	if ($flag & CODECHANGED) $errorstr .= "A new code was made and sent to your email. Check your spam folder.<br>";
	echo $errorstr . "<hr>";
}

if (isset($_GET['error']))
{
	$flag =  $_GET["error"];
	showerrors($flag);
}

/* for testing show user their activation code */
if (isset($_SESSION['userObject']))
{
	$user = unserialize($_SESSION['userObject']);
	if ($user->loggedIn() && !$user->isActivated())
		echo "For the purpose of testing, your activation code is: " . $user->getActivationCode() . ", but please do check if you received the code in your email!<br>";
}
?>
