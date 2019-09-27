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

/* password_verify is not available on my server without this include */
if (!function_exists("password_verify"))
{
	require_once "/usr/share/php/password_compat/password.php";
}

function checkemailexists($connection, $email)
{
	/*
	 * check that the supplied email exists
	 */
	$statement = $connection->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
	if (!$statement)
	{
		/*
		 * FIXME: handle this better, we shouldn't just die..
		 * failed to prepare statement, return false
		 */
		dbdisconnect($connection);
		die("fatal internal database error");
	}

	$statement->bind_param('s', $email);

	if (!$statement->execute())
	{
		/* 
		 * FIXME: handle this better, we shouldn't just die..
		 * failed to execute our statement
		 */
		dbdisconnect($connection);
		die("fatal internal database error");

	}
	$result = $statement->get_result();
	if($result->num_rows == 1)
	{
		$statement->close();
		return true;
	}
	$statement->free_result();
	$statement->close();
	return false;
}

function comparehash($connection, $password, $email)
{
	/*
	 * compare the supplied password with the hash in the database for user $email
	*/
	$return = false;
	$statement = $connection->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
	if (!$statement)
	{
		/*
		 * FIXME: handle this better, we shouldn't just die..
		 * failed to prepare statement, return false
		 */
		dbdisconnect($connection);
		die("fatal internal database error");
	}

	$statement->bind_param('s', $email);

	if (!$statement->execute())
	{
		/* 
		 * FIXME: handle this better, we shouldn't just die..
		 * failed to execute our statement
		 */
		dbdisconnect($connection);
		die("fatal internal database error");

	}
	$result = $statement->get_result();
	if($result->num_rows == 1)
	{
		$row = $result->fetch_assoc();
		if (password_verify($password, $row['password']))
		{
			/* password is correct */
			$return = true;
		}
		$statement->free_result();
	}
	/* close statement */
	$statement->close();
	return $return;
}

?>
