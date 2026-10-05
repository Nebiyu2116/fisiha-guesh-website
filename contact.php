<?php
// Fisiha Guesh Import & Export - secure contact form handler.
declare(strict_types=1);

const RECIPIENT = 'fisihasamuel12@gmail.com';
const MAX_NAME = 100;
const MAX_EMAIL = 254;
const MAX_PHONE = 30;
const MAX_SUBJECT = 150;
const MAX_MESSAGE = 5000;

function fail(string $message): never {
    http_response_code(400);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Message not sent</title><link rel="stylesheet" href="css/global.css"></head><body><main class="section wrap"><h1>Message not sent</h1><p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p><a class="btn dark" href="contact.html">Back to Contact</a></main></body></html>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

session_start();
if (!empty($_SESSION['contact_last_sent']) && (time() - (int)$_SESSION['contact_last_sent']) < 60) {
    fail('Please wait about one minute before sending another message.');
}

// Honeypot: real visitors should never fill this hidden field.
if (!empty($_POST['website'] ?? '')) {
    header('Location: contact.html?sent=1');
    exit;
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || mb_strlen($name) > MAX_NAME) fail('Please enter a valid name.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > MAX_EMAIL) fail('Please enter a valid email address.');
if (mb_strlen($phone) > MAX_PHONE) fail('Please enter a shorter phone number.');
if (mb_strlen($subject) > MAX_SUBJECT) fail('Please enter a shorter subject.');
if ($message === '' || mb_strlen($message) > MAX_MESSAGE) fail('Please enter a message of 1–5,000 characters.');

// Prevent header injection. The visitor's address is used only as Reply-To.
foreach ([$name, $email, $phone, $subject] as $value) {
    if (preg_match('/[\r\n]/', $value)) fail('Invalid form data.');
}

$subject = $subject !== '' ? $subject : 'Website Business Inquiry';
$safeSubject = preg_replace('/[^\p{L}\p{N}\s\-_,.!?&()]/u', '', $subject) ?: 'Website Business Inquiry';

$body = "New website inquiry\n\n"
      . "Name: {$name}\n"
      . "Email: {$email}\n"
      . "Phone: " . ($phone !== '' ? $phone : 'Not provided') . "\n"
      . "Subject: {$safeSubject}\n\n"
      . "Message:\n{$message}\n";

$headers = "MIME-Version: 1.0\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n"
         . "From: Fisiha Guesh Website <" . RECIPIENT . ">\r\n"
         . "Reply-To: {$email}\r\n";

if (!mail(RECIPIENT, $safeSubject, $body, $headers)) {
    fail('The message could not be sent by the hosting mail server. Please contact us by WhatsApp or email.');
}

$_SESSION['contact_last_sent'] = time();
header('Location: contact.html?sent=1');
exit;
