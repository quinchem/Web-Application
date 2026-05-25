<?php

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'thydoanle@gmail.com';

    $mail->Password = 'uoae ozei ymtl xswb';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;

    $mail->setFrom('thydoanle@gmail.com', 'TEST');

    $mail->addAddress('thydoanle@gmail.com');

    $mail->isHTML(true);

    $mail->Subject = 'Test Mail';

    $mail->Body = 'Hello test';

    $mail->send();

    echo 'GUI THANH CONG';

} catch (Exception $e) {

    echo $mail->ErrorInfo;
}