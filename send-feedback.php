<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

$SMTP_HOST = 'mail.leaders.qa';
$SMTP_PORT = 465;
$SMTP_USER = 'website@leaders.qa';
$SMTP_PASS = 'Website@2026';

$TO_EMAIL  = 'info@leaders.qa';
$TO_NAME   = 'Leaders Training Centre';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$feedback = trim($_POST['feedback'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $feedback === '') {
    exit('Missing required fields');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('Invalid email format');
}

$eName     = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$eEmail    = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$ePhone    = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$eFeedback = nl2br(htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8'));

$bodyHtml = "
<h2>New Customer Feedback</h2>
<p><strong>Name:</strong> $eName</p>
<p><strong>Email:</strong> $eEmail</p>
<p><strong>Phone:</strong> $ePhone</p>
<hr>
<p><strong>Feedback:</strong></p>
<p>$eFeedback</p>
";

$bodyText = "New Customer Feedback\n"
          . "Name: $name\n"
          . "Email: $email\n"
          . "Phone: $phone\n\n"
          . "Feedback:\n$feedback";

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0;

    $mail->isSMTP();
    $mail->Host       = $SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = $SMTP_USER;
    $mail->Password   = $SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = $SMTP_PORT;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($SMTP_USER, 'Website Feedback Form');
    $mail->addAddress($TO_EMAIL, $TO_NAME);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'New Customer Feedback';
    $mail->Body    = $bodyHtml;
    $mail->AltBody = $bodyText;

    $mail->send();

    header('Location: thank-you.html');
    exit;

} catch (Exception $e) {
    echo "<h1 style='color:red'>EMAIL FAILED</h1>";
    echo "<p>" . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . "</p>";
}
?>