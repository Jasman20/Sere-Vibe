<?php
session_start();
include'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT photo FROM user_profiles WHERE id = $user_id";
$result = mysqli_query($conn, $query);

$photo = 'default.png'; // fallback
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if (!empty($row['photo'])) {
        $photo = $row['photo'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Main - Sere-Vibe</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      transition: background 0.3s, color 0.3s;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #6a1b9a;
      color: white;
      padding: 10px 20px;
    }

    .nav-left {
      display: flex;
      align-items: center;
    }

    .logo-text {
      font-size: 24px;
      font-weight: bold;
      margin-left: 10px;
    }

    .profile-icon {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
    }

    .nav-right button, .nav-right a {
      margin-left: 10px;
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      font-size: 16px;
      text-decoration: none;
    }

    .container {
      padding: 20px;
      max-width: 960px;
      margin: 0 auto;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .card {
      background: linear-gradient(135deg, #f8bbd0, #d1c4e9);
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 160px;
      font-size: 22px;
      color: #4a148c;
      font-weight: bold;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      animation: fadeIn 0.5s ease-in-out;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .card a {
      color: inherit;
      text-decoration: none;
    }

    #footer {
      text-align: center;
      padding: 20px;
      font-size: 14px;
      color: #666;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
      .logo-text {
        font-size: 20px;
      }
      .card {
        font-size: 18px;
        height: 140px;
      }
    }

    .dark-mode {
      background-color: #1e1e1e;
      color: #f5f5f5;
    }

    .dark-mode .navbar {
      background-color: #333;
    }

    .dark-mode .card {
      background: linear-gradient(135deg, #424242, #616161);
      color: #fff;
    }

    .dark-mode #footer {
      color: #aaa;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="nav-left">
      <img src="uploads/<?php echo htmlspecialchars($photo); ?>" alt="Profile Picture" class="profile-icon">
      <span class="logo-text">Sere-Vibe</span>
    </div>
    <div class="nav-right">
      <button onclick="toggleDarkMode()">🌓</button>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="container">
    
     <div class="grid">
      <a href="ai_chatbot.php" class="card">🤖<br>AI Chatbot</a>
      <a href="https://forms.gle/oNfMLcEsWNZpVTNPA" class="card">📅<br>Weekly Check-ins</a>
      <a href="vibeflow.php" class="card">🧘‍♂️<br>Soul Stretch</a>
      <a href="tips.php" class="card">🧠<br>Mental Wellness</a>
      <a href="journalling.php" class="card">📖<br>My Journal</a>
      <a href="profile.php" class="card">👤<br>My Profile</a>
    </div>
  </div>

  <div id="footer">Feeling meh? We're here to help. 💜</div>

  <script>
    function toggleDarkMode() {
      document.body.classList.toggle("dark-mode");
      localStorage.setItem("theme", document.body.classList.contains("dark-mode") ? "dark" : "light");
    }

    window.onload = () => {
      if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
      }
    };
  </script>
</body>
</html>