<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'haodang1810@gmail.com';
$mail->Password = 'dtli llfb zkua wdxs';
$mail->Port = 587;

$mail->setFrom('haodang1810@gmail.com');
$mail->addAddress('haodang1810@gmail.com');

$mail->isHTML(true);



?>