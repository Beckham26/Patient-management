<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us - SELFhealth</title>

  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)),
                  url('Pic.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
    }

    .header {
      background-color: rgba(0, 0, 0, 0.85);
      padding: 40px 20px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }

    .header h1 {
      font-size: 42px;
      font-weight: 700;
      color: #ff6f61;
    }

    .contact-box {
      max-width: 800px;
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

    .contact-box h2 {
      font-size: 28px;
      margin-bottom: 20px;
      color: #ffc107;
    }

    .contact-box p {
      font-size: 18px;
      line-height: 1.7;
      color: #ffecb3;
      margin-bottom: 15px;
    }

    .contact-box strong {
      color: #fff;
    }

    .footer-note {
      font-size: 16px;
      color: #ccc;
      text-align: right;
      margin-top: 30px;
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
    <h1>Contact Us</h1>
  </div>

  <div class="contact-box container">
    <h2>Get in Touch</h2>
    <p>If you have any questions or need assistance, feel free to reach out to our dedicated team members below:</p>

    <p><strong>Admin:</strong> 0742362731 - Gidion Lutego</p>
    <p><strong>IT Support 1:</strong> 0746098628 - Meshack Sanga</p>
    <p><strong>IT Support 2:</strong> 0767506046 - Mr. Ramos</p>

    <p class="footer-note">
      Best regards,<br>The SELFhealth Team
    </p>

    <a href="index.php" class="btn-back">← Back to Home</a>
  </div>

  <footer>
    &copy; 2025 SELFhealth. Your health, our priority.
  </footer>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
