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
		<title>Activate Account</title>
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
			<div class="content-wrapper">
				<div class="site-content">
<div class="form-style-3">
						<form id="inputform" action="/includes/doactivation.php" method="POST">
							<fieldset id="personaldeets"><legend>Activate account</legend>
								<div id="personalerrormsg">
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/activateerrorflags.php"); ?>
									Please activate your account.<br>
									An email was sent to you with an activation code. Check your spam folder.<br>
									Need a new one sent? Click <a href="/includes/requestcode.php">here</a>.<br>
								</div>
								<label><span>Code</span><input id="code" type="text" class="input-field" name="code"/></label>
								<label><input class="centrebutton" type="submit" form="inputform" action="/includes/doregister.php" value="♡ Activate ♡" /></label>
								Used a fake email? <a href="/includes/dologout.php">Log Out</a> and use a real one, b-baka!!
							</fieldset>
						</form>
					</div>
				</div>
			</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/userclass.php';

if (isset($_SESSION['userObject']))
{
	$user = unserialize($_SESSION['userObject']);
	//echo $user->toString();
	if ($user->isActivated() || !$user->loggedIn())
	{
		/* user is already activated, no need to be here */
		header('Location: /');
		exit;
	}
}
else
{
	/* no user object, no need to be here */
	header('Location: /');
	exit;
}
?>
	</body>
</html>
