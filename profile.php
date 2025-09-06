<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$sql = "SELECT name, age, phone, email, photo FROM user_profiles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    $create = $conn->prepare("INSERT INTO user_profiles (id) VALUES (?)");
    $create->bind_param("i", $user_id);
    $create->execute();

    header("Location: profile.php");
    exit();
}

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $age = $row['age'];
    $phone = $row['phone'];
    $email = $row['email'];
    $photo = $row['photo'];
} else {
    echo "User profile not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="profile-container">
        <img class="profile-pic" src="<?php echo $photo ? 'uploads/' . $photo : 'uploads/avatar.png'?>" />
        <h2><?php echo $name ? $name : "USER"; ?></h2>
        <div class="profile-info">
            <p><strong>Age:</strong> <?php echo $age ? $age : "Not specified"; ?></p>
            <p><strong>Phone:</strong> <?php echo $phone ? $phone : "No phone added"; ?></p>
            <p><strong>Email:</strong> <?php echo $email ? $email : "No email set"; ?></p>
        </div>
        <div class="buttons">
            <a href="edit-profile.php" class="btn">Edit Profile</a>
            <a href="main.php" class="btn back">Back</a>
        </div>
    </div>
</body>
</html>
