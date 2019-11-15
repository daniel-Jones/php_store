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
		<title>Checkout</title>
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
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/constants.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/checkout.php");

if (isset($_SESSION['userObject']))
{
	$user = unserialize($_SESSION['userObject']);
	if (!$user->loggedIn())
	{
		/* uer must login */
		header('Location: /login?error=' . CHECKOUTLOGIN);
		exit;
	}
}
else
{
	/* uer must login */
	header('Location: /login?error=' . CHECKOUTLOGIN);
	exit;
}
$form_data = [];

if (isset($_SESSION['inputform']) && !empty($_SESSION['inputform']))
{
	$form_data = $_SESSION['inputform'];
	unset($_SESSION['inputform']);
}
?>
			<div class="content-wrapper">
				<div class="site-content">
<div class="form-style-3">
	<fieldset id="summarydeets"><legend>Summary</legend>
						<div id="errormsg">
						</div>
						<?php drawsummary();?>
					</fieldset>

				<form id="inputform" action="/includes/docheckout.php" method="POST">
					<fieldset id="checkoutdeets"><legend>Checkout</legend>
						<div id="errormsg">
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/checkouterrorflags.php"); ?>
						</div>
						<label><span>Name <span class="required">*</span></span><input id="name" type="text" class="input-field" name="name" value="<?php if (isset($form_data['name'])) echo $form_data['name']?>" /></label>
						<label><span>Address <span class="required">*</span></span><input id="address" type="text" class="input-field" name="address" value="<?php if (isset($form_data['address'])) echo $form_data['address']?>" /></label>
						<label><span>Town/City <span class="required">*</span></span><input id="town" type="text" class="input-field" name="town" value="<?php if (isset($form_data['town'])) echo $form_data['town']?>" /></label>
						<label><span>Postcode <span class="required">*</span></span><input id="postcode" type="text" class="input-field" name="postcode" value="<?php if (isset($form_data['postcode'])) echo $form_data['postcode']?>" /></label>
						<label><span>Card Type</span><select id="cardtype" name="4" class="select-field">
								<option value="mc">MasterCard</option>
								<option value="ae">American Express</option>
								<option value="vi">Visa</option>
							</select></label>

						<label><span>Card # <span class="required">*</span></span><input id="cardnumber" type="text" class="input-field" name="cardnumber" value="<?php if (isset($form_data['cardnumber'])) echo $form_data['cardnumber']?>" /></label>
						<label><span>Card Name <span class="required">*</span></span><input id="cardname" type="text" class="input-field" name="cardname" value="<?php if (isset($form_data['cardname'])) echo $form_data['cardname']?>" /></label>
</label>
						<label><span>Expiry <span class="required">*</span></span>
							<select id="exp1" class="select-" style="width: 60px; !important">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="12">12</option>

							</select>
							/
							<select id="exp2" class="select-" style="width: 60px; !important">
								<option value="19">19</option>
								<option value="19">20</option>
								<option value="19">21</option>
								<option value="19">22</option>
								<option value="19">23</option>
							</select>

						</label>
						<label><span>CVV <span class="required">*</span></span><input id="cvv" style="width: 60px; !important" type="text" class="input-field" name="cvv" value="<?php if (isset($form_data['cvv'])) echo $form_data['cvv']?>" /></label>
						<label><span> </span><input type="submit" value="♡ arigato gozaimasu ♡" /></label>
					</fieldset>
				</form>
				</div>
			</div>
<?php
/* relative paths are not allowed here */
include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php");
?>
		</div>
	</body>
</html>
