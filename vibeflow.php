<?php
// yoga.php — renamed visually to VibeFlow
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VibeFlow – Anxiety Relief</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom right, #e6ccff, #d9b3ff);
      color: #333;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px;
    }
    h1 {
      font-size: 2.5em;
      color: #4b0082;
      margin-bottom: 0;
    }
    p.subtitle {
      font-size: 1.2em;
      margin-bottom: 30px;
    }
    .session-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      width: 100%;
      max-width: 750px;
    }
    .session-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 20px;
      text-align: center;
      transition: transform 0.2s;
    }
    .session-card:hover {
      transform: scale(1.02);
    }
    .session-card h2 {
      color: #6a0dad;
    }
    .session-card button {
      margin-top: 15px;
      margin-right: 10px;
      padding: 10px 20px;
      background-color: #b266ff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1em;
      transition: background-color 0.2s;
    }
    .session-card button:hover {
      background-color: #9933ff;
    }

    /* 🎨 Controls section */
    .controls {
      margin-top: 30px;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .controls button {
      padding: 10px 20px;
      background-color: #6a0dad;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1em;
      transition: background-color 0.2s;
    }
    .controls button:hover {
      background-color: #4b0082;
    }

    /* Back button styling */
    .back-btn {
      margin-top: 20px;
      padding: 10px 25px;
      background-color: #ff6666;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1em;
      transition: background-color 0.2s;
    }
    .back-btn:hover {
      background-color: #cc0000;
    }
  </style>
</head>
<body>
  <h1>VibeFlow</h1>
  <p class="subtitle">Anxiety Relief • Ease tension and stress</p>

  <div class="session-grid">
    <div class="session-card">
      <h2>Child's Pose</h2>
      <p>Relax your body and mind in a gentle kneeling stretch to release tension.</p>
      <button onclick="speakPose('Relax into Child’s Pose and take a deep breath. Feel the stretch along your spine.')">Voice Guide</button>
    </div>

    <div class="session-card">
      <h2>Cat-Cow</h2>
      <p>Flow through spine movements to improve flexibility and calm the nervous system.</p>
      <button onclick="speakPose('Begin in tabletop. Inhale as you arch into Cow Pose, exhale into Cat Pose as you round your spine.')">Voice Guide</button>
    </div>

    <div class="session-card">
      <h2>Standing Forward Bend</h2>
      <p>Stretch your hamstrings and decompress your spine in this calming pose.</p>
      <button onclick="speakPose('Slowly fold forward from your hips, letting your head hang heavy. Breathe deeply.')">Voice Guide</button>
    </div>

    <div class="session-card">
      <h2>Bridge Pose</h2>
      <p>Open the chest and relieve stress while strengthening your back and legs.</p>
      <button onclick="speakPose('Lie on your back, bend your knees, and lift your hips toward the ceiling. Hold and breathe.')">Voice Guide</button>
    </div>
  </div>

  <!-- 🎛️ Controls -->
  <div class="controls">
    <button onclick="pauseSpeech()">Pause</button>
    <button onclick="resumeSpeech()">Resume</button>
    <button onclick="stopSpeech()">Stop</button>
  </div>

  <!-- 🔙 Back button -->
  <button class="back-btn" onclick="window.location.href='main.php'">Back</button>

  <script>
    let currentUtterance;

    function speakPose(text) {
      stopSpeech(); // Stop any existing speech before starting new
      currentUtterance = new SpeechSynthesisUtterance(text);
      currentUtterance.lang = 'en-US';
      currentUtterance.pitch = 1;
      currentUtterance.rate = 1;
      window.speechSynthesis.speak(currentUtterance);
    }

    function pauseSpeech() {
      if (window.speechSynthesis.speaking && !window.speechSynthesis.paused) {
        window.speechSynthesis.pause();
      }
    }

    function resumeSpeech() {
      if (window.speechSynthesis.paused) {
        window.speechSynthesis.resume();
      }
    }

    function stopSpeech() {
      if (window.speechSynthesis.speaking) {
        window.speechSynthesis.cancel();
      }
    }
  </script>
</body>
</html>
