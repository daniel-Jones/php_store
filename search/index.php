<!DOCTYPE html>
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
?>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Search</title>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta name="keywords" content="manga, yuri, doujin, doujinshi, lewd">
		<meta name="description" content="Buy some Manga, but not really..">
		<meta name="author" content="Daniel Jones">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Cache-control" content="public">
		<link rel="icon" type="image/ico" href="/favicon.png">
		<link rel="stylesheet" href="/css/reset.css">
		<link rel="stylesheet" href="/css/style.css">
	</head>
	<body translate="no">
		<div class="site">
<?php
/* relative paths are not allowed here */
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/itemview.php");
$form_data = [];

	/* create database connection inline for ease */
	static $connection;
	$connection = dbconnect();
	if ($connection == NULL)
	{
		/*
		 * some database error
		 * can't really use the error display feature without looping...
		 * for ease just die for now
		 */
		die("Fatal internal database error");
		exit;
	}

?>
			<div class="content-wrapper">
				<div class="site-content">

<?php
	if (isset($_GET['term']))
		searchbooks($connection, $_GET['term']);
	else
	{
		header("Location: /");
		exit;
	}
?>
				</div>
			</div>
<?php
/* relative paths are not allowed here */
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php");
dbdisconnect($connection);
?>
		</div>

	</body>
</html>
