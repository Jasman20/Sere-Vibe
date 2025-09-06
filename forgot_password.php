<?php
require 'db.php'; // your DB connection

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $sql = "SELECT * FROM users1 WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(50)); // secure token
        $expiry = gmdate("Y-m-d H:i:s", strtotime('+1 hour')); // save in UTC

        // Save token in DB
        $update = "UPDATE users1 SET reset_token='$token', reset_expiry='$expiry' WHERE email='$email'";
        mysqli_query($conn, $update);

        // Generate reset link
        $resetLink = "http://localhost/GJM-PHP/reset_password.php?token=$token";

        // Send reset email with PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jasmankaurchhabra@gmail.com';   // your Gmail address
            $mail->Password   = 'bephvdqesdenetpm';     // 16-char App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('jasmankaurchhabra@gmail.com', 'Sere-Vibe');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hello,<br><br>
                              We received a request to reset your password.<br>
                              Click the link below to reset it:<br><br>
                              <a href='$resetLink'>$resetLink</a><br><br>
                              This link will expire in 1 hour.<br><br>
                              If you didn’t request this, please ignore this email.";

            $mail->send();
        } catch (Exception $e) {
            // Log error for debugging
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }
    }

    // Always show generic response
    echo "✅ If this email is registered, a password reset link has been sent.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <style>
   /* General Styling */
body {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    background: linear-gradient(90deg, #ffd8c5, #d1a9ff);
    display: flex;
    flex-direction: column; /* stack message and container vertically */
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Headings */
h1, h2 {
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    color: #310093;
    text-align: center;
    margin-bottom: 20px;
}

/* Container */
.container {
    width: 90%;
    max-width: 400px;
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    text-align: center;
    margin-top: 10px; /* space below message */
}

/* Form Inputs */
input[type="text"],
input[type="email"],
input[type="password"] {
    width: 80%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 12px;
    font-size: 1em;
}

/* Buttons */
button {
    font-family: cursive;
    padding: 12px;
    background: linear-gradient(90deg, #fdba98, #310093);
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    width: 100%;
    margin-top: 15px;
    font-size: 1em;
    transition: all 0.3s ease;
}

button:hover {
    font-size: 1.05em;
    background: linear-gradient(90deg, #fd8d56, #270174);
}

/* Links */
a {
    text-decoration: none;
    color: #310093;
    font-weight: bold;
    display: inline-block;
    margin-top: 15px;
}

a:hover {
    color: #fd8d56;
}

/* Messages (Success / Error) */
.message {
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 0.95em;
    text-align: center;
    margin-bottom: 10px; /* spacing above container */
    width: fit-content;
    max-width: 90%;
}

.success {
    background-color: #d4edda;
    color: #155724;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
}
</style>

</head>
<body>
    
    <?php if(!empty($message)): ?>
        <div class="message <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
  <div class="container">
      <h2>Forgot Password</h2>
      <form method="POST">
           <input type="email" name="email" placeholder="Enter your registered email" required>
           <button type="submit">Send Reset Link</button>
      </form>
  </div>
</body>
</html>
