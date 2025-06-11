<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Age Calculator</title>
  <style>
    body {
      margin: 0; padding: 0;
      background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
      color: white;
      font-family: 'Segoe UI', sans-serif;
      display: flex; justify-content: center; align-items: center;
      height: 100vh;
    }
    .container {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.2);
      backdrop-filter: blur(10px);
      padding: 30px;
      border-radius: 20px;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 0 15px rgba(0,0,0,0.4);
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    input[type="date"], button {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border-radius: 10px;
      border: none;
      margin-bottom: 15px;
    }
    button {
      border: 2px solid white;
      background: transparent;
      color: white;
      cursor: pointer;
      transition: 0.3s ease;
    }
    button:hover {
      background: rgba(255,255,255,0.2);
    }
    #result {
      opacity: 0;
      transform: scale(0.95);
      transition: 0.5s ease;
      text-align: center;
      margin-top: 20px;
    }
    #result.show {
      opacity: 1;
      transform: scale(1);
    }
    .voice-btn {
      background: rgba(255,255,255,0.1);
      border-radius: 50%;
      width: 45px;
      height: 45px;
      border: 2px solid white;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 10px auto;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Age Calculator</h1>
    <div class="voice-btn" onclick="startListening()">🎤</div>
    <input type="date" id="dob" required />
    <button onclick="calculateAge()">Calculate</button>
    <div id="result"></div>
  </div>

  <script>
    const dobInput = document.getElementById("dob");
    const resultBox = document.getElementById("result");

    // Load saved date
    const saved = localStorage.getItem("dob");
    if (saved) dobInput.value = saved;

    function calculateAge() {
      const dob = dobInput.value;
      if (!dob) return alert("Please select a date");

      localStorage.setItem("dob", dob);

      fetch("calculate.php", {
        method: "POST",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `dob=${dob}`
      })
      .then(res => res.json())
      .then(data => {
        resultBox.innerHTML = `
          🎉 Age: <strong>${data.age}</strong><br>
          📅 Next birthday in: <strong>${data.next}</strong><br>
          ⏳ Last birthday was: <strong>${data.last}</strong>
        `;
        resultBox.classList.add("show");
      });
    }

    // 🎙 Voice Input (Web Speech API)
    function startListening() {
      const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
      recognition.lang = "en-IN";
      recognition.onresult = (event) => {
        const spoken = event.results[0][0].transcript;
        const dateMatch = spoken.match(/(\d{1,2})[^\d]+(\d{1,2})[^\d]+(\d{2,4})/);
        if (dateMatch) {
          const [_, d, m, y] = dateMatch;
          const formatted = `${y.padStart(4,'20')}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`;
          dobInput.value = formatted;
          localStorage.setItem("dob", formatted);
        } else {
          alert("Couldn't recognize a valid date.");
        }
      };
      recognition.start();
    }
  </script>
</body>
</html>
