<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['username'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Email details
    $to = "defandeovan@gmail.com"; 
    $subject = "New message from Flora Trade Indonesia website";
    $body = "Name: $name\nEmail: $email\nMessage:\n$message";
    $headers = "From: $email";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo "Email successfully sent to $to";
    } else {
        echo "Failed to send email.";
    }
}
?>