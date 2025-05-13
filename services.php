<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Services - SELFhealth</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
                  url('Pic.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      scroll-behavior: smooth;
    }

    .header {
      background-color: rgba(0, 0, 0, 0.85);
      text-align: center;
      padding: 40px 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .header h1 {
      font-size: 42px;
      color: #ff6f61;
      font-weight: 700;
    }

    .content-box {
      max-width: 900px;
      margin: 60px auto;
      background-color: rgba(0, 0, 0, 0.55);
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.4);
      animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .content-box p {
      font-size: 18px;
      line-height: 1.8;
      color: #ffecb3;
      margin-bottom: 30px;
    }

    .footer-note {
      font-size: 16px;
      color: #ccc;
      text-align: right;
    }

    .btn-back {
      display: inline-block;
      font-size: 18px;
      background-color: #ff6f61;
      color: #000;
      padding: 12px 30px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s ease;
      margin-top: 30px;
    }

    .btn-back:hover {
      background-color: #e65b55;
      transform: scale(1.05);
    }

    footer {
      text-align: center;
      color: #aaa;
      font-size: 14px;
      padding: 20px 0;
      background-color: rgba(0, 0, 0, 0.65);
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Welcome to SELFhealth Services</h1>
  </div>

  <div class="content-box container">
    <p>
      At SELFhealth, we believe healthcare should be simple, connected, and human-centered.
      Our platform allows you to effortlessly book appointments, monitor your progress, and explore customized health programs.
    </p>

    <p>
      Whether you're scheduling a virtual consultation, accessing lab results, or seeking wellness tips tailored to your goals,
      our system empowers you to take charge of your health journey—all from one place.
    </p>

    <p class="footer-note">
      Best,<br>The SELFhealth Team
    </p>

    <a href="index.php" class="btn-back">← Back to Home</a>
  </div>

  <footer>
    &copy; 2025 SELFhealth. Your health, our priority.
  </footer>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
