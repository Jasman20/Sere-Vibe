<?php
require 'db.php'; // DB connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token is valid and not expired
    $sql = "SELECT * FROM users1 WHERE reset_token='$token' AND reset_expiry > UTC_TIMESTAMP()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password     = $_POST['password'];
            $confirm_pass = $_POST['confirm_password'];

            if ($password !== $confirm_pass) {
                echo "❌ Passwords do not match.";
            } else {
                $newPass = password_hash($password, PASSWORD_DEFAULT);
                $update = "UPDATE users1 
                           SET password='$newPass', reset_token=NULL, reset_expiry=NULL 
                           WHERE reset_token='$token'";
                mysqli_query($conn, $update);

                echo "✅ Password updated successfully. <a href='login.html'>Login</a>";
                exit;
            }
        }
    } else {
        echo "❌ Invalid or expired token. Please request a new reset link.";
    }
} else {
    echo "❌ No token provided.";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <style>
    /* General Styling */
body {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    background: linear-gradient(90deg, #ffd8c5, #d1a9ff);
    display: flex;
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
}

/* Form Inputs */
input[type="text"],
input[type="email"],
input[type="password"] {
    width: 85%;
    padding: 10px;
    margin: 2px 0;   /* increased spacing */
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
    padding: 10px;
    margin: 10px 0;
    border-radius: 12px;
    font-size: 0.95em;
    text-align: center;
}

.success {
    background-color: #d4edda;
    color: #155724;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
}

/* Optional: Center forms using container class */
form {
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <form method="POST">
             <input type="password" name="password" placeholder="Enter new password" required><br><br>
             <input type="password" name="confirm_password" placeholder="Confirm new password" required><br><br>
             <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
