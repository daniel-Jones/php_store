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

/* session and includes */
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/dbconnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/activationcode.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/commonfunctions.php';
/* password_verify is not available on my server without this include */
if (!function_exists("password_verify"))
{
	require_once "/usr/share/php/password_compat/password.php";
}

function checkcaptcha()
{
	/*
	 * check captcha value
	 * see https://github.com/dapphp/securimage
	 */
	$flag = 0;
	include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
	$securimage = new Securimage();

	if ($securimage->check($_POST['captcha_code']) == false)
	{
		/* captcha was incorrect */

		$flag = BADCAPTCHA;
	}
	/* correct captcha will leave the flag as 0 */
	return $flag;
}

function validateform()
{
	/* validate form POST data
	 * if data is invalid, OR it to a flag that we will return as a GET to the register
	 * page which then displays relevant errors
	 */
	$flag = 0;
	if (empty($_POST['firstname'])) $flag |= BADFIRSTNAME;
	if (empty($_POST['lastname'])) $flag |= BADLASTNAME;
	if (empty($_POST['email'])) $flag |= BADEMAIL;
	if (empty($_POST['phone'])) $flag |= BADPHONE;
	if (empty($_POST['password'])) $flag |= BADPASSWORD;
	if (empty($_POST['re-enterpassword'])) $flag |= BADREENTERPASSWORD;

	/*
	 * terribly validate no XSS attempt. seriously, this is bad and NOT ENOUGH.
	 * but this is a quick hack to bypass primitive XSS attempts...
	 * we will use hmtlspecialchars() whenever we print things the user provides
	 */
	$filteredfirstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$filteredlastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	if (strcmp($_POST['firstname'], $filteredfirstname) != 0)
	{
		$flag |= BADFIRSTNAME;
	}
	if (strcmp($_POST['lastname'], $filteredlastname) != 0)
	{
		$flag |= BADLASTNAME;
	}


	/* validate phone is numeric and a sane length */
	if (!is_numeric($_POST['phone']) || strlen($_POST['phone']) < 6) $flag |= BADPHONE;

	/* validate email using handy builtin function that checks against RFC 822 */
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $flag |= BADEMAIL;

	/* ensure password >= 6 characters */
	if (strlen($_POST['password']) < 6) $flag |= BADPASSWORD;

	if (strcmp($_POST['password'], $_POST['re-enterpassword']) != 0) $flag |= PASSWORDMISMATCH;
	return $flag;
}

function dovalidation()
{
	/*
	 * we store multiple errors as a single integer value using bit flags
	 * if you know binary we're ORing error values together and checking them by ANDing later.
	 * see /includes/constants.php for error values
	 */

	$flag = 0;
	$flag |= checkcaptcha();
	$flag |= validateform();
	if ($flag != 0)
	{
		/* there was _some_ error in validation, save POST data into session and return user to register page */
		if (!empty($_POST))
		{
			foreach($_POST as $key => $value)
			{
				$_SESSION['inputform'][$key] = $value;
			}
		}
		/* append generated error code */
		header('Location: /register?error=' . $flag);
		exit;
	}
}

function dbfail($connection)
{
	/* some form of database error occured, tell user to try later */
	header('Location: /register?error=' . DBERROR);
	exit;

}

function registeruser()
{
	static $connection;
	$connection = dbconnect();
	if ($connection == NULL)
	{
		/* some form of database error occured, tell user to try later */
		header('Location: /register?error=' . DBERROR);
		exit;
	}
	/* connected to database successfully */

	/* first check if the email is taken */
	if (checkemailexists($connection, $_POST['email']))
	{
		/* email is taken */
		dbdisconnect($connection);
		header('Location: /register?error=' . EMAILTAKEN);
		exit;
	}

	/*
	 * all users need to activate their account before using the website
	 * they are emailed an activation code of 6 numbers
	 * 5 of those numbers are "random", however the first number is
	 * always going to be known.
	 * the first number is the number of times the user has had
	 * a code is re-emailed to them once upon request.
	 * if the code starts with >1 a code will NOT be remade and resent.
	 * this logic is in /includes/activationcode.php and /includes/userclass.php
	 */
	$activationcode = generatecode(0);
	
	/* prepare our sql statement because of nasty injections */
	$statement = $connection->prepare("INSERT INTO user (firstname, lastname, email,
					   phone, password, registerdate, activated, activationcode)
					   VALUES (?,?,?,?,?,?,?,?)");
	if (!$statement)
	{
		/* failed to prepare statement, fail out */
		dbfail($connection);
	}
	/* bind values */
	$date = date("Y-m-d H:i:s");
	$isactivated = 0;
	/* salt is stored inside the hash itself */
	$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
	/* FIXME: sanitize inputs at some point */
	$statement->bind_param('ssssssis', $_POST['firstname'], $_POST['lastname'],
					   $_POST['email'], $_POST['phone'], $hash,
					   $date, $isactivated, $activationcode);

	if (!$statement->execute())
	{
		/* failed to execute our statement */
		//echo $statement->error;
		dbfail($connection);

	}
	$statement->close();
	dbdisconnect($connection);
	echo "registered fine";
	/* send user activation code FIXME: error handle */
	sendcodetouser($_POST['email'], $activationcode, $_POST['firstname'], $_POST['lastname']);
	header('Location: /login?error=' . LOGINNOW);
}

/* perform validation checks */
dovalidation();

/* reaching here means the data was validated successfully and we can continue with registering the user */
registeruser();
?>
