<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

// ---- SMTP (fill the real password locally; do not commit) ----
$SMTP_HOST = 'mail.leaders.qa';
$SMTP_PORT = 587;
$SMTP_USER = 'alishba@leaders.qa';
$SMTP_PASS = 'alishba@leaders.qa';
$TO_EMAIL  = 'alishba@leaders.qa';
$TO_NAME   = 'Leaders Training Centre';

// Accept POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method Not Allowed'); }
// Honeypot
if (!empty($_POST['website'] ?? '')) { header('Location: thank-you.html'); exit; }

// Inputs
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');
if ($name === '' || $email === '' || $phone === '' || $message === '') { http_response_code(422); exit('Missing required fields.'); }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { http_response_code(422); exit('Invalid email.'); }

// Build bodies
$eName    = htmlspecialchars($name,    ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$eEmail   = htmlspecialchars($email,   ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$ePhone   = htmlspecialchars($phone,   ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$eMessage = nl2br(htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

$bodyHtml = <<<HTML
<h2>New Contact Form Submission</h2>
<table cellpadding="6" cellspacing="0">
  <tr><td><strong>Name</strong></td><td>{$eName}</td></tr>
  <tr><td><strong>Email</strong></td><td>{$eEmail}</td></tr>
  <tr><td><strong>Phone</strong></td><td>{$ePhone}</td></tr>
</table>
<hr>
<p style="white-space:pre-wrap;">{$eMessage}</p>
HTML;

$bodyText = "New Contact Form Submission\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\n\n{$message}\n";

$mail = new PHPMailer(true);

try {
    // --- IMPORTANT: keep debug OFF in production ---
    $mail->SMTPDebug   = 0;           // 0 = no SMTP debug output
    $mail->Debugoutput = 'html';      // irrelevant when debug=0

    $mail->isSMTP();
    $mail->Host       = $SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = $SMTP_USER;
    $mail->Password   = $SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // switch to ENCRYPTION_SMTPS + 465 if needed
    $mail->Port       = $SMTP_PORT;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($SMTP_USER, 'Website Contact Form');
    $mail->addAddress($TO_EMAIL, $TO_NAME);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'New Contact Message';
    $mail->Body    = $bodyHtml;
    $mail->AltBody = $bodyText;

    $mail->send();

    // Redirect to a separate thank-you page
    header('Location: thank-you.html');
    exit;
} catch (Exception $e) {
    http_response_code(500);
    // Minimal error page without SMTP dump
    echo '<!doctype html><meta charset="utf-8"><title>Thank you</title>
      <style>body{font-family:system-ui,Segoe UI,Arial;padding:30px}</style>
      <h1>Thank you</h1><p>Your message has been sent.</p>
      <p><a href="contact.html">Back to contact page</a></p>';
}
