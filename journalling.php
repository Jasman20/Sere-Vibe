<?php
session_start();
$conn = new mysqli("localhost", "root", "", "serevibe");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_name = $_SESSION['user'] ?? 'guest';
$secretKey = 'your-secret-key-32-chars'; // Must be 32 characters for AES-256

function encryptEntry($plainText, $secretKey) {
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt($plainText, 'AES-256-CBC', $secretKey, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptEntry($encodedData, $secretKey) {
    $data = base64_decode($encodedData);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $secretKey, 0, $iv);
}

$message = "";
$today = date("Y-m-d");
$streak = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entry'], $_POST['mood'])) {
    $entry = $_POST['entry'];
    $mood = $_POST['mood'];
    $g1 = $_POST['gratitude1'];
    $g2 = $_POST['gratitude2'];
    $g3 = $_POST['gratitude3'];
    $encrypted_entry = encryptEntry($entry, $secretKey);

    $check = $conn->prepare("SELECT entry_date, streak_count FROM journal_entries WHERE user_name = ? ORDER BY entry_date DESC LIMIT 1");
    $check->bind_param("s", $user_name);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_date = $row['entry_date'];
        $date_diff = (strtotime($today) - strtotime($last_date)) / (60 * 60 * 24);
        if ($date_diff == 1) {
            $streak = $row['streak_count'] + 1;
        } elseif ($date_diff == 0) {
            $message = "You already wrote a journal today!";
        }
    }
    $check->close();

    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO journal_entries (user_name, mood, encrypted_entry, gratitude1, gratitude2, gratitude3, entry_date, streak_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $user_name, $mood, $encrypted_entry, $g1, $g2, $g3, $today, $streak);
        $stmt->execute();
        $stmt->close();
        $message = "Journal entry saved! Current streak: $streak day(s).";
    }
}

$entries = [];
if (isset($_GET['view'])) {
    $stmt = $conn->prepare("SELECT mood, encrypted_entry, gratitude1, gratitude2, gratitude3, entry_date, streak_count FROM journal_entries WHERE user_name = ? ORDER BY entry_date DESC");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['entry'] = decryptEntry($row['encrypted_entry'], $secretKey);
        $entries[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Journaling App</title>
  <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #ffd6d6, #d2afff);
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 600px;
        margin: auto;
        background: #fff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    textarea, select, input[type="text"], button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    button {
        background: linear-gradient(to right, #ff7b7b, #7f55ff);
        color: white;
        border: none;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background: linear-gradient(to right, #e06666, #6a44e0);
    }

    .message {
        text-align: center;
        color: #2e2e2e;
        margin-top: 10px;
        font-weight: bold;
    }

    .entry {
        margin-top: 20px;
        background: #fafafa;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    @media (max-width: 600px) {
        .container {
            padding: 15px;
        }
    }
  </style>
</head>
<body>
<div class="container">
    <h2>My Daily Journal</h2>
    <form method="POST">
        <label for="entry">Journal Entry:</label>
        <textarea id="entry" name="entry" required></textarea>

        <label for="mood">Mood:</label>
        <select name="mood" id="mood" required>
            <option value="">Select Mood</option>
            <option>😊 Happy</option>
            <option>😢 Sad</option>
            <option>😡 Angry</option>
            <option>😌 Calm</option>
            <option>😔 Meh</option>
        </select>

        <label>Gratitude List:</label>
        <input type="text" name="gratitude1" placeholder="Grateful for 1" required>
        <input type="text" name="gratitude2" placeholder="Grateful for 2" required>
        <input type="text" name="gratitude3" placeholder="Grateful for 3" required>

        <button type="submit">Save Entry</button>
    </form>

    <div class="message"><?= $message ?></div>

        <form method="GET">
        <button type="submit" name="view">View Past Entries</button>
    </form>

    <form action="main.php" method="get" style="margin-top: 10px;">
        <button type="submit">⬅ Back to Main</button>
    </form>


    <?php foreach ($entries as $entry): ?>
        <div class="entry">
            <strong><?= $entry['entry_date'] ?> (<?= $entry['mood'] ?>)</strong><br>
            <p><?= nl2br($entry['entry']) ?></p>
            <ul>
                <li>🙏 <?= $entry['gratitude1'] ?></li>
                <li>🙏 <?= $entry['gratitude2'] ?></li>
                <li>🙏 <?= $entry['gratitude3'] ?></li>
            </ul>
            <small>Streak: <?= $entry['streak_count'] ?> day(s)</small>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
