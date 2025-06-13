<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$check = mysqli_query($conn, "SELECT * FROM user_profiles WHERE id = $user_id");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO user_profiles (user_id) VALUES ($user_id)");
}

$success = '';
$error = '';

// Fetch existing user data
$query = "SELECT name, email, age, phone, photo FROM user_profiles WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];

    // Handle file upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $photo_name = basename($_FILES['profile_photo']['name']);
        $target_path = "uploads/" . $photo_name;

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_path)) {
            $photo = $photo_name;
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $photo = $user['photo']; // keep existing photo
    }

    if (!$error) {
        $update = "UPDATE user_profiles SET name='$name', email='$email', age='$age', phone='$phone', photo='$photo' WHERE id=$user_id";
        if (mysqli_query($conn, $update)) {
            $success = "Profile updated successfully.";

            // Refresh values in $user to reflect changes in form
            $user['name'] = $name;
            $user['email'] = $email;
            $user['age'] = $age;
            $user['phone'] = $phone;
            $user['photo'] = $photo;
        } else {
            $error = "Update failed: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="profile.css">
    <style>
        body {
            background: radial-gradient(#ffede4, #6600c6bf);
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .profile-container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        input[type="text"], input[type="number"], input[type="file"] {
            display: block;
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }
        .btn {
            background-color: #6600c6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #4a0080;
        }
        .back {
            margin-top: 10px;
            display: inline-block;
        }
        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .success {
            background-color: #c6f6c6;
            color: #086108;
        }
        .error {
            background-color: #f6c6c6;
            color: #8f0000;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>

        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
    <?php if (!empty($user['photo'])): ?>
        <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Profile Picture" style="width:120px;height:120px;border-radius:50%;margin-bottom:10px;">
    <?php endif; ?>

    <label for="profile_photo">Change Profile Photo:</label>
    <input type="file" name="profile_photo" id="profile_photo" accept="image/*">

    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>">

    <label for="age">Age:</label>
    <input type="number" name="age" id="age" value="<?= htmlspecialchars($user['age'] ?? '') ?>">

    <label for="phone">Phone:</label>
    <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">

    <button type="submit" class="btn">Save</button>
</form>


        <a href="profile.php" class="btn back">← Back to Profile</a>
    </div>
</body>
</html>
