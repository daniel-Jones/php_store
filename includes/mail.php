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

require("/var/www/phpmailer/PHPMailer.php");
require("/var/www/phpmailer/SMTP.php");
require("/var/www/phpmailer/Exception.php");

function sendmail($email, $subject, $message)
{
	/*
	 * send email to a specified email
	 * we store smtp credentials in the same /var/www/manga_creds.ini file
	 */
	$config = parse_ini_file('/var/www/manga_creds.ini'); 
	$mail = new PHPMailer\PHPMailer\PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'ssl';
	$mail->Host = $config['smtphost'];
	$mail->Port = (int)$config['smtpport']; /* cast to int */
	$mail->IsHTML(true); /* make it a gross html email */
	$mail->Username = $config['smtpuser'];
	$mail->Password = $config['smtppassword'];
	$mail->SetFrom($config['smtpuser']);
	$mail->AddBcc("daniel@danieljon.es"); /* BCC me in so I know it isn't spamming ... */
	$mail->Subject = $subject;
	$mail->Body = $message;
	$mail->AddAddress($email);

	if(!$mail->Send())
	{
		echo "Mailer Error: " . $mail->ErrorInfo;
		return false;
	} else
	{
		echo "Message has been sent";
		return true;
	}

}

?>
