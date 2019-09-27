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

/*
 * generic script for connecting to the database
 *
 * we _DO NOT_ store credentials in the script itself,
 * instead they are stored in /var/www/manga_creds.ini 
 * which is NOT visible to the public with contents like this:
 *
 * [database]
 * servername = localhost
 * username = name
 * password = password
 * dbname = dbname
 *
 */
function dbconnect() {
	$config = parse_ini_file('/var/www/manga_creds.ini'); 
	$connection = mysqli_connect($config['servername'],
				     $config['username'],
				     $config['password'],
				     $config['dbname']);

	if (mysqli_connect_errno())
	{
		$connection = NULL;
	}
	return $connection;
}

function dbdisconnect($connection)
{
	return mysqli_close($connection);
}

/*
 * use like this:
 *
 * static $connection;
 * $connection = dbconnect();
 * if ($connection == NULL)
 * {
 *	 echo "connection error: " . mysqli_connect_error();
 * }
 * else
 * {
 *	dbdisconnect($connection);
 * }
 */
?>
