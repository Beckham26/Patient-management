<?php
$message = '';
$alertClass = 'alert-info'; // Default alert class
$icon = '<i class="fas fa-info-circle me-2"></i>'; // Default icon

if (isset($_GET['role']) && $_GET['role'] == 'doctor') {
    $message = htmlspecialchars($_GET['message']);
    if (strpos($message, 'success') !== false) {
        $alertClass = 'alert-success';
        $icon = '<i class="fas fa-check-circle me-2"></i>';
    } else {
        $alertClass = 'alert-danger';
        $icon = '<i class="fas fa-exclamation-triangle me-2"></i>';
    }
} elseif (isset($_GET['usernameID'])) {
    $usernameID = htmlspecialchars($_GET['usernameID']);
    $message = "{$icon}Registration successful! 
    Your Username ID is: <strong>" . $usernameID . "</strong>.
     You can now log in.";
    $alertClass = 'alert-success';
    $icon = '<i class="fas fa-check-circle me-2"></i>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status - SELFhealth</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00796b;
            --primary-dark: #004d40;
            --primary-light: #b2dfdb;
            --accent-color: #ff6f61;
            --text-dark: #263238;
            --text-light: #eceff1;
            --bg-gradient: linear-gradient(135deg, rgba(0,121,107,0.7) 0%, rgba(0,77,64,0.7) 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url("pica.jpg") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Add a subtle overlay for better text readability */
            z-index: 0;
        }

        .status-container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 1;
        }

        h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 2rem;
        }

        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background-color: var(--primary-light);
            border-color: var(--primary-color);
            color: var(--primary-dark);
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }

        .login-link {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            margin-top: 1.5rem;
        }

        .login-link:hover {
            background-color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="status-container">
        <h2>Registration Status</h2>
        <div class="alert <?php echo $alertClass; ?>" role="alert">
            <?php echo $icon . $message; ?>
        </div>
        <a href="login.php" class="login-link">Go to Login</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>