<?php
// signup.php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $name = $_POST['signup-name'];
    $email = $_POST['signup-email'];
    $password = $_POST['signup-password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Connect to database
    $conn = new mysqli("localhost", "root", "", "serevibe");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users1 WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email already registered!";
    } else {
        // Insert user into users1 table
        $stmt = $conn->prepare("INSERT INTO users1 (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            // 🟩 Get the newly inserted user ID
            $user_id = $stmt->insert_id;

            // 🟩 Insert default profile into user_profiles
            $default_age = 0;
            $default_phone = '';
            $default_photo = 'default.jpg';

            $profile_stmt = $conn->prepare("INSERT INTO user_profiles (id, name, age, phone, email, photo) VALUES (?, ?, ?, ?, ?, ?)");
            $profile_stmt->bind_param("isisss", $user_id, $name, $default_age, $default_phone, $email, $default_photo);
            $profile_stmt->execute();
            $profile_stmt->close();

            // Set session and redirect
            $_SESSION['user'] = $name;
            $_SESSION['user_id'] = $user_id;
            header("Location: main.php");
            exit();
        } else {
            echo "Signup failed: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
