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
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/createuserclass.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/cartclass.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/itemview.php");

/* check we have an isbn POST and a count OR modify POST set, if not, just dump them back to the main screen */
if (!isset($_POST['isbn']) || !(!isset($_POST['count']) || !isset($_POST['modify'])))
{
	header("Location: /");
	exit;
}

/* ensure the book exists */

static $connection;
$connection = dbconnect();
if ($connection == NULL)
{
	/* some database error FIXME: do something better */
	header("Location: /");
	exit;
}

$book = getbookdetails($connection, $_POST['isbn']);
if (!isset($book['title']))
{
	/* book doesn't exist in the database, redirect to / */
	dbdisconnect($connection);
	header("Location: /");
	exit;
}

$user = unserialize($_SESSION['userObject']);
if (isset($_POST['modify']))
{
	$user->getCart()->modifyBook($_POST['isbn'], $_POST['modify']);

}

if (isset($_POST['count']))
{
	$user->getCart()->addBook($_POST['isbn'], $_POST['count']);
}

//echo $user->getCart()->printBooks();
$_SESSION['userObject'] = serialize($user);
dbdisconnect($connection);
if (isset($_POST['goto']))
{
	if (strcmp($_POST['goto'], "gotocheckout") == 0)
	{
		header("Location: /checkout");
		exit;
	}
}
header("Location: /cart/");
?>
