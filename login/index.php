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
		<title>Login</title>
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
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/userclass.php");
$form_data = [];

if (isset($_SESSION['userObject']))
{
	/* check if the user is logged in and redirect them away */
	$user = unserialize($_SESSION['userObject']);
	if ($user->loggedIn())
	{
		echo "Thinks logged in";
		header('Location: /');
		exit;
	}
}

if (isset($_SESSION['inputform']) && !empty($_SESSION['inputform']))
{
	$form_data = $_SESSION['inputform'];
	unset($_SESSION['inputform']);
}
?>
			<div class="content-wrapper">
				<div class="site-content">
					<div class="form-style-3">
						<form id="inputform" action="/includes/dologin.php" method="POST">
							<fieldset id="personaldeets"><legend>Login</legend>
								<div id="personalerrormsg">
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/loginerrorflags.php"); ?>
								</div>
								<label><span>Email <span class="required">*</span></span><input id="email" type="text" class="input-field" name="email" value="<?php if (isset($form_data['email'])) echo $form_data['email']?>" /></label>
								<label><span>Password <span class="required">*</span></span><input id="phone" type="password" class="input-field" name="password" value="<?php if (isset($form_data['password'])) echo $form_data['password']?>" /></label>
								<label><input class="centrebutton" type="submit" form="inputform" action="/includes/doregister.php" value="♡ Login ♡" /></label>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
<?php
/* relative paths are not allowed here */
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php");
?>
		</div>
	</body>
</html>
