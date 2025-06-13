<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AI Chatbot</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 0;
    }

    #chatbot-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #6a5acd;
      color: #fff;
      border: none;
      padding: 12px 20px;
      border-radius: 25px;
      font-size: 16px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: background 0.3s ease;
      z-index: 9999;
    }

    #chatbot-toggle:hover {
      background-color: #5a4ebc;
    }

    #chatbot-container {
      position: fixed;
      bottom: 80px;
      right: 20px;
      width: 350px;
      height: 500px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      border-radius: 12px;
      overflow: hidden;
      z-index: 9998;
      background-color: #fff;
    }

    .hidden {
      display: none;
    }
  </style>
</head>
<body>

  <!-- Chatbot Button -->
  <button id="chatbot-toggle">💬 Chat</button>

  <!-- Chatbot Window -->
  <div id="chatbot-container" class="hidden">
    <iframe
      src="https://cdn.botpress.cloud/webchat/v2.2/shareable.html?configUrl=https://files.bpcontent.cloud/2025/03/15/16/20250315163342-2HZHTEYE.json"
      width="100%"
      height="100%"
      style="border: none;">
    </iframe>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const chatbotToggle = document.getElementById('chatbot-toggle');
      const chatbotContainer = document.getElementById('chatbot-container');

      chatbotToggle.addEventListener('click', () => {
        chatbotContainer.classList.toggle('hidden');
      });
    });
  </script>
</body>
</html>
