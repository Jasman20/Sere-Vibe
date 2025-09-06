<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['login-name'];
    $password = $_POST['login-password'];

    $conn = new mysqli("localhost", "root", "", "serevibe");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, password FROM users1 WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $db_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $db_password)) {
        $_SESSION['user'] = $name;
        $_SESSION['user_id'] = $user_id;

        // Fetch photo from user_profiles table
        $photo_stmt = $conn->prepare("SELECT photo FROM user_profiles WHERE id = ?");
        $photo_stmt->bind_param("i", $user_id);
        $photo_stmt->execute();
        $photo_result = $photo_stmt->get_result();
        if ($photo_row = $photo_result->fetch_assoc()) {
            $_SESSION['photo'] = $photo_row['photo'];
        }

        header("Location: main.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


