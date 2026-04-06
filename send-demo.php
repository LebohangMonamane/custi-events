<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['cellphone'] ?? '');
    $org = sanitize($_POST['organisation'] ?? '');
    $comment = sanitize($_POST['comment'] ?? '');

    if (!$name || !$email || !$phone || !$org) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    $recipients = ['info@custi.africa'];
    $subject = 'Demo Request from ' . $org;

    $message = "Name: $name\n";
    $message .= "Email: $email\n";
    $message .= "Phone: $phone\n";
    $message .= "Organisation: $org\n";
    $message .= "Comment: $comment\n";

    $headers = "From: info@custi.africa\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    $sent = true;
    foreach ($recipients as $recipient) {
        if (!mail($recipient, $subject, $message, $headers)) {
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
