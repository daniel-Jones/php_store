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

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/mail.php';

function notrandom($length)
{
	/* generate a not-so-random string of digits of length $length */
	$count = 0;
	$rand = '';
	while ($count < $length)
	{
		$randdigit = mt_rand(0, 9);

		$rand .= $randdigit;
		$count++;
	}
	return $rand;
}

function generatecode($trynumber)
{
	return ++$trynumber . notrandom(5);
}

function verifycode($connection, $email, $code)
{
	/*
	 * check that the supplied email exists
	 */
	$flag = 0;
	$statement = $connection->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
	if (!$statement)
	{
		/*
		 * failed to prepare statement, set error flag
		 */
		$flag |= DBERROR;
	}

	$statement->bind_param('s', $email);

	if (!$statement->execute())
	{
		/* 
		 * failed to execute our statement, set error flag
		 */
		$flag |= DBERROR;

	}
	$result = $statement->get_result();
	if($result->num_rows == 1)
	{
		$row = $result->fetch_assoc();
		if (!strcmp($code, $row['activationcode']) == 0)
			$flag |= BADACTIVATIONCODE;
	}

	$statement->free_result();
	$statement->close();
	return $flag;
}

function sendcodetouser($email, $code, $firstname, $lastname)
{
	/*
	 * this function sends the user their activation code
	 */
	$subject = "Manga Store activation code";
	$message = $firstname . " " . $lastname . ",<br><br>Your activation code for <a href='https://manga.gnupluslinux.com'>https://manga.gnupluslinux.com</a>  is: " . $code . "<br><br>If you did not expect this email, please ignore it.";

	/* send it */

	/* FIXME: error handle */
	sendmail($email, $subject, $message);
}

?>
