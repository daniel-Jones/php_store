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
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/dbconnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/userclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/activationcode.php';

function validatecode($email, $code)
{
	/*
	 * attempt to verify the activation code
	 */
	$flag = 0;
	static $connection;
	$connection = dbconnect();
	if ($connection == NULL)
	{
		/* some database error */
		header('Location: /activate?error=' . DBERROR);
		exit;
	}

	/* call second function to do database logic (see /includes/activationcode.php) */
	$flag |= verifycode($connection, $email, $code);

	dbdisconnect($connection);
	return $flag;
}

/* retrieve our user object */
$user = NULL;
if (isset($_SESSION['userObject']))
{
	$user = unserialize($_SESSION['userObject']);
	if ($user->loggedIn())
	{
		/*
		 * user is logged in.
		 */
		;
	}
	else
	{
		/* for some reason the user object is not logged in? how are you here? */
		header('Location: /');
		exit;
	}
}
else
{
	/* for some reason there is no user object? how are you here? */
	header('Location: /');
	exit;
}

/* typical error checking stuff */
$flag = 0;
$flag |= validatecode($user->getEmail(), $_POST['code']);
if ($flag != 0)
{
	/* there was _some_ error in login, return user to login page */
	if (!empty($_POST))
	{
		foreach($_POST as $key => $value)
		{
			$_SESSION['inputform'][$key] = $value;
		}
	}
	header('Location: /activate?error=' . $flag);
	exit;
}

/* activation code verified, activate account */
$user->setActivated(true);
$user->syncUserToDatabase();
$_SESSION['userObject'] = serialize($user);

header('Location: /');
exit;

?>
