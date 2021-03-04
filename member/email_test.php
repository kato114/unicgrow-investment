<?php
require_once("config.php");
include("function/send_mail.php");
$SmtpServer = "localhost";
$SmtpPort = "11";
$SmtpUser = "11";
$SmtpPass = "11";
$from_email = "noreply@unicgrow.com"; 
$to = "unicgrow@gmail.com";
$title = "test cron";
$db_msg = "test cron";
$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $db_msg);	
?>