<?php
session_start();

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    include 'config.php';

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, name, password, role, confirmed FROM user WHERE usernameID = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            if ($user['role'] == 'doctor' && !$user['confirmed']) {
                echo "<script>alert('Your account has not been confirmed by the admin.');</script>";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] == 'patient') {
                    header("Location: patient_dashboard.php");
                    exit();
                } elseif ($user['role'] == 'doctor') {
                    header("Location: doctor_dashboard.php");
                    exit();
                } elseif ($user['role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                    exit();
                }
            }
        } else {
            echo "<script>alert('Invalid username or password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SELFhealth</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #00796b;
            --primary-dark: #004d40;
            --primary-light: #b2dfdb;
            --accent-color: #ff6f61;
            --text-dark: #263238;
            --text-light: #eceff1;
            --bg-gradient: linear-gradient(135deg, rgba(0,121,107,0.9) 0%, rgba(0,77,64,0.9) 100%);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: url('loginpic.jpeg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }
        
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .logo i {
            font-size: 2.5rem;
            color: var(--primary-color);
            background: var(--primary-light);
            padding: 1rem;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 121, 107, 0.2);
        }
        
        h2 {
            margin: 0 0 1.5rem 0;
            color: var(--primary-color);
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            position: relative;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 121, 107, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
            display: block;
            text-decoration: none;
            margin-top: 1rem;
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .input-group-text {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            border: 1px solid var(--primary-color);
        }
        
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 576px) {
            .form-container {
                padding: 1.5rem;
                margin: 1rem;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-container {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="logo">
        <i class="fas fa-user-circle"></i>
    </div>
    <h2>Welcome Back</h2>
    <form method="post" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username ID</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username ID" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
        </div>
        
        <button type="submit" name="login" class="btn btn-primary">Login</button>
        <a href="register.php" class="btn-secondary">Don't have an account? Register</a>
    </form>
</div>

<script>
    // Add animation to form elements
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach((input, index) => {
            input.style.opacity = '0';
            input.style.transform = 'translateY(10px)';
            input.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            input.style.transitionDelay = `${index * 0.1}s`;
            
            setTimeout(() => {
                input.style.opacity = '1';
                input.style.transform = 'translateY(0)';
            }, 100);
        });
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>