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

function printHeader()
{
	$notloggedin = '<a href="/login">Login</a></li><li><a href="/register">Register</a>';
	$loggedin = '<a href="/includes/dologout.php">Logout</a>';
	if (isset($_SESSION['userObject']))
	{
		$user = unserialize($_SESSION['userObject']);
		if ($user->loggedIn())
		{
			/*
			 * user is logged in.
			 * check if user needs to activate account
			 * redirect if so
			 * */
			if ($user->isActivated() == false)
			{
				header('Location: /activate');
				exit;
			}
			echo $loggedin;
		}
		else
			echo $notloggedin;
	}
	else
	{
		echo $notloggedin;
	}

}

?>
<div class="site-header">
				<h1 class="site-title"><a href="/"><img src="/images/lewd.png" alt="Not lewd manga" style=""></a></h1>
				<div class="site-navigation">
					<ul>
					<li><a href="/">Home</a></li> <li><a href="/cart/">Cart</a> <li><?php printHeader(); ?></li></li>
		&nbsp;&nbsp; <li><input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for manga.." title="Type in a name"></li>
					</ul>
				</div>
			</div>
<?php 
if (strcmp($_SERVER['HTTP_HOST'], "manga.gnupluslinux.com") != 0)
{
	echo "<br><br><marquee>NOTICE</marquee><h1>This website will not function correctly when running on _your_ server. It has dependencies and an environment you _do not_ have. Test the website at <A href='https://manga.gnupluslinux.com'>https://manga.gnupluslinux.com</a>.</h1>";
}

?>
