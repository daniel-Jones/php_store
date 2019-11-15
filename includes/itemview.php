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

function calculatediscountprice($price, $discount)
{
	return $price - ($price * $discount);
}

function getseriesdetail($connection, $seriesid, $desiredrow)
{
	/*
	 * holy shit I am actually calling the database for every request.
	 * this is disgusting and I felt like trahs writing this shit.
	 * TODO implement some form of caching?
	 *
	 * NOTE: this has been replaced by just inner joining tables..
	 * keeping to show how terrible hacks are
	 */

	$statement = $connection->prepare("SELECT * FROM series WHERE seriesid = ? LIMIT 1");
	$statement->bind_param('i', $seriesid);

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
	$row = $result->fetch_assoc();
	return $row["$desiredrow"];
}

function getbookdetails($connection, $isbn)
{
	/*
	 * get book details and return row associated with it
	 */
	$flag = 0;
	$statement = $connection->prepare("SELECT * FROM book INNER JOIN series ON book.seriesid = series.seriesid WHERE isbn = ? LIMIT 1");
	$statement->bind_param('s', $isbn);

	if (!$statement->execute())
	{
		/* 
		 * FIXME: handle this better, we shouldn't just die..
		 * failed to execute our statement
		 */
		dbdisconnect($connection);
		die("Fatal database error");
		exit;
	}
	$result = $statement->get_result();
	$row = $result->fetch_assoc();
	return $row;
}

function showbook($connection, $isbn)
{
	if (isset($_SESSION["lastviewedbook"]))
	{
		if ($_SESSION["lastviewedbook"] != $isbn)
		{
			$_SESSION["lastviewedbook"] = $isbn;
		}
		else
		{
			unset($_SESSION["lastviewedbook"]);
		}
	}
	else
	{
		$_SESSION["lastviewedbook"] = $isbn;
	}

	$row = getbookdetails($connection, $isbn);
	/* if the book doesn't exist, just dump them back on the home page */
	if ($row["title"] == "")
	{
		header("Location: /");
		exit;	
	}
	/* set some fluff details if the user has javascript enabled */

	echo '
		<script>
			document.title = "' . $row["title"] . ' - Not Lewd Manga";
			document.getElementById("viewitemlegend").innerHTML="' . $row["title"] . '";
		</script>
	     ';
	$pricetext = "$" . $row["price"];
	if ($row["salepercent"] != 0.0)
	{
		$newprice = calculatediscountprice($row["price"], $row["salepercent"]);
		$pricetext = "<span class=\"oldprice\">$" . $row["price"] . "</span> $" . number_format((float)$newprice, 2, '.', '') . " " . ($row["salepercent"] * 100) . "% off!";
	}


	echo '<div class="mangadesc">
		<img class="viewimage" src="/images/manga/' . $row["imagename"] . '">
		<br>
		' . $row["title"] . '<br>
		<input type="hidden" id="isbn" name="isbn" value="'.$row["isbn"].'">
		<input type="hidden" id="count" name="count" value="1">
		Author: ' . $row["author"] . '<br>
		Date published: ' . $row["datepublished"] . '<br>
		Publisher: ' . $row["publisher"] . '<br>
		Pages: ' . $row["pagecount"] . '<br>
		Generes: ' . $row["genre"] . '<br>
		' . $pricetext . '<br>
		<button class="p-add">Add to Cart</button>
		Description:<br>' . $row["description"] . '
		</div>
';

}

function drawsearchbar()
{
	 echo '
	<form action="/search/" method="GET">
	<div class="InputAddOn">
	  <input id="term" name="term" class="InputAddOn-field" placeholder="Search for manga">
	  <button class="InputAddOn-item">search</button>
	</div>
	</form>
	';
}

function showbooks()
{
	/*
	 * crappily show all books
	 */

	$bookquery = "SELECT title, price, datepublished, publisher, salepercent, imagename, pagecount, isbn, seriesid FROM book ORDER BY datepublished";
	static $connection;
	$connection = dbconnect();
	if ($connection == NULL)
	{
		/* some database error */
		echo "database error.";
		exit;
	}
	drawsearchbar();

	$query = $connection->query($bookquery);
	if ($query->num_rows > 0)
	{
		while($row = $query->fetch_assoc())
		{
			//echo "title: " . $row["title"]. " price: " . $row["price"] . "<br>";
			/* calculate price */
			$pricetext = "$" . $row["price"];
			if ($row["salepercent"] != 0.0)
			{
				$newprice = calculatediscountprice($row["price"], $row["salepercent"]);
				$pricetext = "<span class=\"oldprice\">$" . $row["price"] . "</span> $" . number_format((float)$newprice, 2, '.', '') . " " . ($row["salepercent"] * 100) . "% off!";

			}
			echo '
				<div class="p-float">
						<div class="p-float-in">
							<a href="/view?manga=' . $row["isbn"] . '"><img class="p-img" src="/images/manga/' . $row["imagename"] . '"/> </a>
							<div class="p-name">' . $row["title"] . '</div>
							<div class="p-date">' . date('d/m/Y', strtotime($row["datepublished"])) . '</div>
							<div class="p-price">' . $pricetext . '</div><br>
							<a href="/view?manga=' . $row["isbn"] . '"><button class="p-add">View Item</button></a>
						</div>
					</div>
			     ';

		}
	}
	else
	{
		echo "No manga found.";
	}

	dbdisconnect($connection);
}


function searchbooks($connection, $term)
{
	/*
	 * crappily show searched books
	 */
	$param = "%{$_GET['term']}%";
	$stmt = $connection->prepare("SELECT * FROM book WHERE title LIKE ? ORDER BY datepublished");
	if ($stmt == false)
	{
		die("fatal database error");
	}
	drawsearchbar();

	$stmt->bind_param("s", $param);
	$stmt->execute();

	$result = $stmt->get_result();
	$numrows = mysqli_num_rows($result);
	if ($numrows == 0)
	{
		echo "No manga found for search term.";
		return;
	}
	while ($row = $result->fetch_assoc())
	{
		/* calculate price */
		$pricetext = "$" . $row["price"];
		if ($row["salepercent"] != 0.0)
		{
			$newprice = calculatediscountprice($row["price"], $row["salepercent"]);
			$pricetext = "<span class=\"oldprice\">$" . $row["price"] . "</span> $" . number_format((float)$newprice, 2, '.', '') . " " . ($row["salepercent"] * 100) . "% off!";

		}
		echo '
				<div class="p-float">
						<div class="p-float-in">
							<a href="/view?manga=' . $row["isbn"] . '"><img class="p-img" src="/images/manga/' . $row["imagename"] . '"/> </a>
							<div class="p-name">' . $row["title"] . '</div>
							<div class="p-date">' . date('d/m/Y', strtotime($row["datepublished"])) . '</div>
							<div class="p-price">' . $pricetext . '</div><br>
							<a href="/view?manga=' . $row["isbn"] . '"><button class="p-add">View Item</button></a>
						</div>
					</div>
			     ';

	}


}

?>
