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
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/commonfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/constants.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/itemview.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/userclass.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/cartview.php';
session_start();

function verifydetails()
{
	/* verify checkout details */
	$flag = 0;
	if (isset($_SESSION['userObject']))
	{
		$user = unserialize($_SESSION['userObject']);
		if (!$user->loggedIn())
		{
			$flag |= CHECKOUTLOGIN;
			return;
		}
		/* names can contain special character, numbers ... names are weird */
		if (empty($_POST['name'])) $flag |= BADFIRSTNAME;
		/* addresses probably have some rule, but I don't care.. */
		if (empty($_POST['address'])) $flag |= BADADDRESS;
		/* towns/cities probably have some rule, but I don't care.. */
		if (empty($_POST['town'])) $flag |= BADTOWN;
		/* post code, must be digits */
		if (empty($_POST['postcode']) || !is_numeric($_POST['postcode']))
			$flag |= BADPOSTCODE;
		/* card number, must be digits and >= 16 digits */
		if (empty($_POST['cardnumber']) || !is_numeric($_POST['cardnumber'])
			|| strlen($_POST['cardnumber']) < 16)
			$flag |= BADCARDNUMBER;
		/* names can contain special character, numbers ... names are weird */
		if (empty($_POST['cardname'])) $flag |= BADCARDNAME;
		/* cvv, must be 3 digits */
		if (empty($_POST['cvv']) || !is_numeric($_POST['cvv'])
			|| strlen($_POST['cvv']) != 3)
			$flag |= BADCVV;
	}
	else
	{
		$flag |= CHECKOUTLOGIN;
	}
	return $flag;
}

$flag = 0;
$flag |= verifydetails();
if ($flag != 0)
{
	/* some error in validation */
	if (!empty($_POST))
	{
		foreach($_POST as $key => $value)
		{
			$_SESSION['inputform'][$key] = $value;
		}
	}
	header('Location: /checkout?error=' . $flag);
	exit;
}

/*
 * now we can insert the order/payment into the database
 * we don't actually store the bank information provided
 */
static $connection;
$connection = dbconnect();
if ($connection == NULL)
{
	/* some database error */
	header("Location: /checkout?error=" . DBERROR);
	exit;
}

/* create the order */
/* we know the user exists now */
$user = unserialize($_SESSION['userObject']);
$statement = $connection->prepare("INSERT INTO manga.order (userid, orderdate,
	totalcost) 
	VALUES (?,?,?)");
if (!$statement)
{
	/* failed to prepare statement, fail out */
	echo $connection->error;
	die("");
	dbdisconnect($connection);
	header("Location: /checkout?error=" . DBERROR);
	exit;
}
/* bind values */
$date = date("Y-m-d H:i:s");
$id = $user->getUserId();
$books = $user->getCart()->getBooks();
$totalprice = calculatetotalprice($connection, $books);
$totalprice = number_format((float)$totalprice, 2, '.', '');
$statement->bind_param('isd', $id, $date,
	$totalprice);

if (!$statement->execute())
{
	/* failed to execute our statement */
	echo $statement->error;
	dbdisconnect($connection);
	header("Location: /checkout?error=" . DBERROR);
	exit;

}
$statement->close();

$orderid = mysqli_insert_id($connection);

/* create order items */
foreach ($books as $isbn=>$count)
{
	$statement = $connection->prepare("INSERT INTO manga.orderdetail (orderid, bookisbn, quantity) 
		VALUES (?,?,?)");
	if (!$statement)
	{
		/* failed to prepare statement, fail out */
		echo $connection->error;
		dbdisconnect($connection);
		header("Location: /checkout?error=" . DBERROR);
		exit;
	}
	/* bind values */
	$statement->bind_param('isi', $orderid, $isbn,
		$count);

	if (!$statement->execute())
	{
		/* failed to execute our statement */
		echo $statement->error;
		dbdisconnect($connection);
		header("Location: /checkout?error=" . DBERROR);
		exit;

	}
	$statement->close();
}

/* create payment */
$statement = $connection->prepare("INSERT INTO manga.payment (orderid, paymentdate, totalpaid) 
	VALUES (?,?,?)");
if (!$statement)
{
	/* failed to prepare statement, fail out */
	echo $connection->error;
	die("");
	dbdisconnect($connection);
	header("Location: /checkout?error=" . DBERROR);
	exit;
}
/* bind values */
$statement->bind_param('isd', $orderid, $date, $totalprice);

if (!$statement->execute())
{
	/* failed to execute our statement */
	echo $statement->error;
	dbdisconnect($connection);
	header("Location: /checkout?error=" . DBERROR);
	exit;

}
$statement->close();


$user->getCart()->emptyCart();
$_SESSION['userObject'] = serialize($user);
dbdisconnect($connection);
header("Location: /cart?error=" . ORDERCOMPLETE);

?>
