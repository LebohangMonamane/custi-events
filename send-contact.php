<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = sanitize($_POST['firstName'] ?? '');
    $lastName = sanitize($_POST['lastName'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!$firstName || !$lastName || !$email || !$message) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    $recipients = ['info@custi.africa'];
    $mailSubject = 'Contact Form Enquiry';
    if ($subject) {
        $mailSubject .= ': ' . $subject;
    }

    $mailBody = "First Name: $firstName\n";
    $mailBody .= "Last Name: $lastName\n";
    $mailBody .= "Email: $email\n";
    $mailBody .= "Subject: " . ($subject ?: 'Not provided') . "\n";
    $mailBody .= "Message: $message\n";

    $headers = "From: info@custi.africa\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    $sent = true;
    foreach ($recipients as $recipient) {
        if (!mail($recipient, $mailSubject, $mailBody, $headers)) {
            $sent = false;
            error_log('Mail failed for: ' . $recipient);
        }
    }

    if ($sent) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send email']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
