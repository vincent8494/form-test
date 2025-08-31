<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validate input
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo "Please fill out all fields.";
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please enter a valid email address.";
        exit;
    }
    
    // Load Composer's autoloader
    require 'vendor/autoload.php';
    
    // Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // SMTP server (e.g., smtp.gmail.com for Gmail)
        $mail->SMTPAuth = true;
        $mail->Username = 'fettymikev@gmail.com';  // Your email
        $mail->Password = 'hodt tqlf nhpp pqap';     // Your app password (not your regular password)
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('assignmenthelp6435@gmail.com');  // The email where you want to receive messages
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = "Re: $subject";
        $mail->Body    = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; }
                .header { background-color: #2c3e50; padding: 20px; color: white; }
                .content { padding: 20px; }
                .message { 
                    background-color: #f8f9fa; 
                    padding: 20px; 
                    border-left: 4px solid #3498db;
                    margin: 20px 0;
                    border-radius: 4px;
                }
                .footer { 
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #eee;
                    color: #777;
                    font-size: 0.9em;
                }
                .details { 
                    background-color: #f5f7fa;
                    padding: 15px;
                    border-radius: 4px;
                    margin: 15px 0;
                }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1 style='margin: 0; color: white;'>New Website Inquiry</h1>
                <p style='margin: 5px 0 0 0; color: #ecf0f1; font-size: 0.9em;'>" . date('F j, Y') . "</p>
            </div>
            
            <div class='content'>
                <h2 style='color: #2c3e50; margin-top: 0;'>{$subject}</h2>
                
                <div class='details'>
                    <p style='margin: 5px 0;'><strong>From:</strong> {$name}</p>
                    <p style='margin: 5px 0;'><strong>Email:</strong> {$email}</p>
                    <p style='margin: 5px 0;'><strong>Date:</strong> " . date('F j, Y, g:i a') . "</p>
                </div>
                
                <h3 style='color: #2c3e50;'>Message:</h3>
                <div class='message'>
                    " . nl2br(htmlspecialchars($message)) . "
                </div>
                
                <div class='footer'>
                    <p>This is an automated message from your website contact form. Please do not reply directly to this email.</p>
                    <p>&copy; " . date('Y') . " Your Company Name. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $mail->AltBody = "
            NEW WEBSITE INQUIRY
            " . str_repeat("=", 50) . "
            
            Subject: {$subject}
            Date: " . date('F j, Y, g:i a') . "
            From: {$name} <{$email}>
            
            " . str_repeat("-", 50) . "
            MESSAGE:
            " . str_repeat("-", 50) . "
            {$message}
            
            " . str_repeat("-", 50) . "
            This is an automated message from your website contact form.
            ";
        
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        http_response_code(500);
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // Not a POST request, redirect to the form
    header("Location: index.html");
    exit;
}
