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

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/commonfunctions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/dbconnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/itemview.php';

function calculatetotalprice($connection, $books)
{
	/* FIXME: querying the database twice for each item is fucking dumb */
	$totalprice = 0.0;
	foreach ($books as $isbn=>$count)
	{
		$row = getbookdetails($connection, $isbn);
		$perunit = $row["price"];
		if ($row["salepercent"] != 0.0)
		{
			$perunit = calculatediscountprice($perunit, $row["salepercent"]);
		}

		$totalprice += $perunit * $count;
	}


	return $totalprice + ($totalprice * 0.10);
}

function showcart()
{
	/* show the shopping cart */

	$user = unserialize($_SESSION['userObject']);
	$books = $user->getCart()->getBooks();
	if (empty($books))
	{
		echo '<ul id="carttext">No items in cart</ul>';
		return;
	}
	/* generate and show the cart page content */
	static $connection;
	$connection = dbconnect();
	if ($connection == NULL)
	{
		/*
		 * some database error
		 */
		die("Fatal internal database error");
		exit;
	}

	/* calculate total cost */
	$totalcost = calculatetotalprice($connection, $user->getCart()->getBooks());
	echo '<div><ul id="carttext">Total price (inc 10% GST): $' . number_format((float)$totalcost, 2, '.', '') . '</ul><br>
	      <form id="checkout" action="/checkout" method="POST"><label><input class="centrebutton" type="submit" form="checkout" value="♡ Checkout ♡" /></label></form>
<br>';
	foreach ($user->getCart()->getBooks() as $isbn=>$count)
	{
		$row = getbookdetails($connection, $isbn);
		/* calculate cost */
		$perunit = $row["price"];
		if ($row["salepercent"] != 0.0)
		{
			$perunit = calculatediscountprice($perunit, $row["salepercent"]);
		}
		$perunit = $perunit * $count;
		echo '
			<div>
			<a href="/view/?manga=' . $isbn . '">
			<img id="cartimage" src="/images/manga/' . $row["imagename"] . '"> </a>
			<ul id="carttext">
			<a href="/view/?manga=' . $isbn . '">' . $row["title"] . '</a><br>
			price for volume(s): $' . number_format((float)$perunit, 2, '.', '') . '<br>
			<form id="'.$isbn . '" action="/includes/addtocart.php" method="POST">
			<input type="hidden" id="isbn" name="isbn" value="' . $isbn . '">
			quantity: <input id="modify" name="modify" style="width: 3ch;" value="' . $count . '"> &nbsp;<a href="/includes/removefromcart.php?isbn=' . $isbn . '">remove from cart</a><br><br>
			<label><input class="centrebutton" type="submit" form="'. $isbn . '" action="/includes/doregister.php" value="modify quantity" /></label>
			</form>
			</ul>
			<br style="clear: both;">
			</div> 
			<br>
		';
	}

	dbdisconnect($connection);
}

function showcheckoutcart()
{
	/* copypasta fuck neat code at this point */

	$user = unserialize($_SESSION['userObject']);
	$books = $user->getCart()->getBooks();
	if (empty($books))
	{
		header("Location: /");
		exit;
	}
	/* generate and show the cart page content */
	static $connection;
	$connection = dbconnect();
	if ($connection == NULL)
	{
		/*
		 * some database error
		 */
		die("Fatal internal database error");
		exit;
	}

	/* calculate total cost */
	$totalcost = calculatetotalprice($connection, $user->getCart()->getBooks());
	echo '<div><ul id="carttext">Total price (inc 10% GST): $' . number_format((float)$totalcost, 2, '.', '') . '</ul><br>
<br>';
	foreach ($user->getCart()->getBooks() as $isbn=>$count)
	{
		$row = getbookdetails($connection, $isbn);
		/* calculate cost */
		$perunit = $row["price"];
		if ($row["salepercent"] != 0.0)
		{
			$perunit = calculatediscountprice($perunit, $row["salepercent"]);
		}
		$perunit = $perunit * $count;
		echo '
			<div>
			<a href="/view/?manga=' . $isbn . '">
			<img id="cartimage" src="/images/manga/' . $row["imagename"] . '"> </a>
			<ul id="carttext">
			<a href="/view/?manga=' . $isbn . '">' . $row["title"] . '</a><br>
			price for volume(s): $' . number_format((float)$perunit, 2, '.', '') . '<br>
			<form id="'.$isbn . '" action="/includes/addtocart.php" method="POST">
			<input type="hidden" id="isbn" name="isbn" value="' . $isbn . '">
			<input type="hidden" id="goto" name="goto" value="gotocheckout">
			quantity: <input id="modify" name="modify" style="width: 3ch;" value="' . $count . '"> &nbsp;<a href="/includes/removefromcart.php?isbn=' . $isbn . '&goto=checkout">remove from cart</a><br><br>
			<label><input class="centrebutton" type="submit" form="'. $isbn . '" action="/includes/doregister.php" value="modify quantity" /></label>
			</form>
			</ul>
			<br style="clear: both;">
			</div> 
			<br>
		';
	}

	dbdisconnect($connection);
}

?>
