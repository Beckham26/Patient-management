<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SELFhealth - Patient Management</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
                  url('aboutpic.jpeg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      scroll-behavior: smooth;
    }

    .navbar {
      background-color: rgba(0, 0, 0, 0.85);
      box-shadow: 0 4px 6px rgba(0,0,0,0.5);
    }

    .navbar-brand {
      color: #ff6f61 !important;
      font-weight: bold;
      font-size: 28px;
      letter-spacing: 1px;
    }

    .nav-link {
      color: #fff !important;
      font-size: 17px;
      margin-right: 15px;
    }

    .nav-link:hover {
      color: #ffcccb !important;
    }

    .hero-section {
      padding-top: 120px;
      padding-bottom: 80px;
      animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .hero-title {
      font-size: 54px;
      color: #ff6f61;
      font-weight: 700;
    }

    .hero-subtitle {
      font-size: 28px;
      color: #fff;
      margin-top: 20px;
      margin-bottom: 30px;
    }

    .hero-text {
      font-size: 18px;
      line-height: 1.8;
      color: #ffecb3;
    }

    .cta-btn {
      margin-top: 30px;
      font-weight: bold;
      font-size: 18px;
      background-color: #ff6f61;
      color: #000;
      padding: 12px 30px;
      border: none;
      border-radius: 8px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .cta-btn:hover {
      background-color: #e65b55;
      transform: scale(1.05);
    }

    footer {
      background-color: rgba(0, 0, 0, 0.7);
      color: #bbb;
      text-align: center;
      padding: 15px 0;
      font-size: 14px;
      margin-top: 60px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">SELFhealth</a>
      <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon text-white"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link active" href="index.php">HOME</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php">SERVICES</a></li>
          <li class="nav-item"><a class="nav-link" href="contacts.php">CONTACTS</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container text-center">
      <h1 class="hero-title">Integrated Patient Management System</h1>
      <h2 class="hero-subtitle">Hello and Welcome!</h2>
      <p class="hero-text">
        We’re delighted to have you here at SELFhealth, where your health and well-being are our top priorities.
        Our integrated system connects you with trusted professionals and personalized health services.
        <br><br>
        Whether you're looking to consult with doctors, track your health journey, or explore wellness solutions,
        we ensure a smooth, secure, and supportive experience.
        <br><br>
        Warm regards,<br>
        <strong>The SELFhealth Team</strong>
      </p>
      <a href="login.php" class="btn cta-btn">JOIN US</a>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    &copy; 2025 SELFhealth. All Rights Reserved.
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
