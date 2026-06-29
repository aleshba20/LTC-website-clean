<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$course = $_POST['course'] ?? '';
$brochure = $_POST['brochure'] ?? '';

$allowedBrochures = [

    'pdf/Admin and secretarial (1) (2).pdf',

    'pdf/ACCA - Qualification Handbook - Ver A 2022 - For Review 2.pdf',

    'pdf/CMA HANDBOOK.pdf',

    'pdf/LTC (IFRS).pdf',

    'pdf/ACCA vs CPA.pdf',

    'pdf/new1.pdf',

    'pdf/new2.pdf',

    'pdf/25_IELTS Handbook (1).pdf',

    'pdf/pte.pdf',

    'pdf/Medical.pdf',

    'pdf/Leaderships and public speaking.pdf',

    'pdf/30_MICROSOFT POWER BI- 2023 (2) (1) (1).pdf',

    'pdf/MS Excel  Intermediate to Advanced (1).pdf',

    'pdf/6_Photography_Course Handbook_2024-VER-A (6).pdf',

    'pdf/4_Video Editing_Course Handbook_2024-VER-A.pdf',

    'pdf/5_Graphic Design _Course Handbook_2024-VER-A.pdf',

    'pdf/MS Office.pdf',

    'pdf/23_Digital Marketing Handbook1.pdf'

];

if (!in_array($brochure, $allowedBrochures)) {
    die('Invalid brochure selected.');
}

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'mail.leaders.qa';
    $mail->SMTPAuth = true;
    $mail->Username = 'website@leaders.qa';
    $mail->Password = 'Website@2026';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('website@leaders.qa', 'Leaders Website');

    $mail->addAddress('info@leaders.qa');

    $mail->Subject = 'New Brochure Lead - ' . $course;

    $mail->Body = "
Name: $name

Phone: $phone

Email: $email

Course: $course

Brochure: $brochure
";

    $mail->send();

    header("Location: " . str_replace(' ', '%20', $brochure));

    exit();

} catch (Exception $e) {

    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>