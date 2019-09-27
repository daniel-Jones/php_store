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


require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/dbconnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/activationcode.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/constants.php';

class User
{
	/* 
	 * user class
	 */

	private $userid;
	private $firstname;
	private $lastname;
	private $email;
	private $activationcode;
	private $isactivated;

	/* constructor */
	public function __construct($userid, $email)
	{
		$this->userid = $userid;
		$this->email = $email;
	}

	public function __destruct()
	{

	}

	public function getUserId()
	{
		return $this->userid;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getActivationCode()
	{
		return $this->activationcode;
	}

	public function getFirstName()
	{
		return htmlspecialchars($this->firstname);
	}

	public function getLastName()
	{
		return htmlspecialchars($this->lastname);
	}

	public function isActivated()
	{
		return $this->isactivated;
	}
	public function setActivated($activated)
	{
		$this->isactivated = $activated;
	}

	public function loggedIn()
	{
		return ($this->userid != -1);
	}

	public function login($connection, $email)
	{
		/*
		 * here we fill the member fields with data from the database
		 * this is run once the user logs in so we are _sure_ the data
		 * associated with the provided email is theirs
		 */
		$flag = 0;
		$statement = $connection->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
		if (!$statement)
		{
			/*
			 * failed to prepare statement
			 */
			$flag |= DBERROR;
		}

		$statement->bind_param('s', $email);

		if (!$statement->execute())
		{
			/* 
			 * failed to execute our statement
			 */
			$flag |= DBERROR;

		}
		if ($flag == 0)
		{
			/* no database error occured, we're good to go */
			$result = $statement->get_result();
			if($result->num_rows == 1)
			{
				$row = $result->fetch_assoc();
				$this->userid = $row['userid'];
				$this->firstname = $row['firstname'];
				$this->lastname = $row['lastname'];
				$this->email = $row['email'];
				$this->activationcode = $row['activationcode'];
				$this->isactivated = $row['activated'];
				$statement->free_result();
			}
			/* close statement */
			$statement->close();
		}
		return $flag;
	}

	function logout()
	{
		/*
		 * "log out" the user session
		 * this works because everything once logged in relies
		 * on the userid/email
		 *
		 * we ensure the cart is NOT destroyed by not destroying the user object
		 * FIXME: store cart in db?
		 */
		$this->userid = -1;
		$this->email = NULL;
	}

	public function toString()
	{
		return "userid: " . $this->userid . "firstname: " . $this->firstname . 
		        "lastname: " . $lastname . " useremail: " . $this->email . " activation code: " . $this->activationcode . " is activated: " . $this->isactivated;
	}

	public function syncUserToDatabase()
	{
		/* sync the user object to the database */
		static $connection;
		$connection = dbconnect();
		if ($connection == NULL)
		{
			/* some database error FIXME: do something more elegant */
			die("fatal database error");
		}

		$statement = $connection->prepare("UPDATE user SET activationcode = ?, activated = ? WHERE email = ?");
		if (!$statement)
		{
			/*
			 * FIXME: handle this better, we shouldn't just die..
			 * failed to prepare statement, return false
			 */
			dbdisconnect($connection);
			die("fatal internal database error");
		}

		$statement->bind_param('sis', $this->activationcode, $this->isactivated, $this->email);

		if (!$statement->execute())
		{
			/* 
			 * FIXME: handle this better, we shouldn't just die..
			 * failed to execute our statement
			 */
			echo $statement->error;
			dbdisconnect($connection);
			die("fatal internal database error");

		}
		dbdisconnect($connection);
	}

	public function setNewActivationCode()
	{
		/*
		 * we allow the user to create a new activation code and have it resent
		 * only once. we track this by using the first digit of the activation code,
		 * if it is 1, the user can have an ew code generated, if it is a 2, they cannot.
		 * (we increment the first digit by one)
		 */
		$flag = 0;
		/* check to see if user is allowed to regenerate a code */
		if ($this->activationcode[0] < 2)
		{
			$newcode = generatecode($this->activationcode[0]);
			echo "can have new code";
			echo "<br> current code: " . $this->activationcode;
			echo "<br> new code: " . $newcode;
			$this->activationcode = $newcode;
			$flag |= CODECHANGED;
		}
		else
		{
			$flag |= NEWCODENOTALLOWED;
			echo "can not have new code";
		}
		return $flag;
	}
}
?>
