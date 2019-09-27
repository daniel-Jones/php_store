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
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/commonfunctions.php';

function login()
{
	/*
	 * attempt to log the user in
	 */
	$flag = 0;
	static $connection;
	$connection = dbconnect();
	if ($connection == NULL)
	{
		/* some database error */
		header('Location: /login?error=' . DBERROR);
		exit;
	}

	/* check if the user exists */
	if (!checkemailexists($connection, $_POST['email']))
	{
		/* email does not exist */
		$flag |= UNKNOWNUSER;
	}

	/* check supplied password hash against stored one */
	if ($flag == 0 && !comparehash($connection, $_POST['password'], $_POST['email']))
	{
		/* password does not match record */
		$flag |= BADPASSWORD;
	}
	
	/* check if we have any errors */
	if ($flag == 0)
	{
		/*
		 * setup our user object
		 * a user object may already exist from creating a shopping cart so
		 * we must check that, then tell that object we are logged in
		 */

		$user =  NULL;
		if (isset($_SESSION['userObject']))
		{
			/* user object already exists, unserialize it */
			echo "user object set already<br>";
			$user = unserialize($_SESSION['userObject']);
		}
		else
		{
			/* need to create a new user object */
			echo "new user object<br>";
			$user = new User(-1, NULL);
			$_SESSION['userObject'] = serialize($user);
		}

		/* setup user class with relevant information */
		$flag |= $user->login($connection, $_POST['email']);
		/* re serialize user object into the session */
		$_SESSION['userObject'] = serialize($user);
		//echo ($user->loggedIn()) ? "true" : "false";
	}
	dbdisconnect($connection);
	return $flag;
}

$flag = 0;
$flag |= login();
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
	header('Location: /login?error=' . $flag);
	exit;
}

/* login was a success */
$user = unserialize($_SESSION['userObject']);

header('Location: /');
exit;

?>
