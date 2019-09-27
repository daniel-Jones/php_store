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
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/userclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/mail.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/constants.php';
function requestcode()
{
	$flag;
	if (isset($_SESSION['userObject']))
	{
		$user = unserialize($_SESSION['userObject']);
		if ($user->loggedIn())
		{
			$flag = $user->setNewActivationCode();
			if ($flag == CODECHANGED)
			{
				$user->syncUserToDatabase();
				sendcodetouser($user->getEmail(), $user->getActivationCode(),
					$user->getFirstName(), $user->getLastName());
				$_SESSION['userObject'] = serialize($user);
			}
		}
		else
		{
			/* user is not logged in, shouldn't be here */
			header("Location: /");
			exit;
		}
	}
	else
	{
		/* no user object, shouldn't be here */
		header("Location: /");
		exit;
	}
	return $flag;
}
$flag = requestcode();
/* no matter what, flag will be set */
header('Location: /activate?error=' . $flag);

?>
