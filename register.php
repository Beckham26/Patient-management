<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $email = $_POST['email'] ?? '';  // Optional
    $expertise = $_POST['expertise'] ?? '';  // Optional for doctors

    // Generate usernameID based on role
    $sql = "SELECT MAX(id) as max_id FROM user WHERE role='$role'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $new_id = ($row['max_id'] ? $row['max_id'] + 1 : 1000);

    $usernameID = ($role == 'patient') ? 'P' . $new_id : 'D' . $new_id;

    $sql = "INSERT INTO user (name, dob, gender, usernameID, password, role, phone_number) 
            VALUES ('$name', '$dob', '$gender', '$usernameID', '$password', '$role', '$phone_number')";

    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id;

        if ($role == 'patient') {
            $sql = "INSERT INTO patient (user_id) VALUES ('$user_id')";
        } elseif ($role == 'doctor') {
            $sql = "INSERT INTO doctor (user_id, expertise) VALUES ('$user_id', '$expertise')";
        }

        if ($conn->query($sql) === TRUE) {
            if ($role == 'doctor') {
                $message = "Registration successful. Your Username ID is " . $usernameID . ". Please wait for admin to verify your account.";
            } else {
                $message = "Registration successful. Your Username ID is " . $usernameID;
            }
            
            $redirect_url = 'show_message.php?role=' . urlencode($role) . '&message=' . urlencode($message) . '&usernameID=' . urlencode($usernameID);
            header("Location: " . $redirect_url);
            exit();
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SELFhealth</title>
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
            background: url('registerpic.jpeg') no-repeat center center fixed;
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
        
        .input-group-text {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            border: 1px solid var(--primary-color);
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
        <i class="fas fa-user-plus"></i>
    </div>
    <h2>Create Account</h2>
    <form method="post" action="register.php">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone number" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                <input type="date" class="form-control" id="dob" name="dob" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select" id="gender" name="gender" required>
                <option value="" disabled selected>Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Create password" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="role" class="form-label">Register As</label>
            <select class="form-select" id="role" name="role" required>
                <option value="" disabled selected>Select role</option>
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
            </select>
        </div>
        
        <div id="expertiseField" class="mb-3" style="display:none;">
            <label for="expertise" class="form-label">Medical Expertise</label>
            <select class="form-select" id="expertise" name="expertise">
                <option value="" disabled selected>Select expertise</option>
                <option value="HIV">HIV</option>
                <option value="MALARIA">Malaria</option>
                <option value="TYPHOID">Typhoid</option>
                <option value="UTI">UTI</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Register Now</button>
        <a href="login.php" class="btn-secondary">Already have an account? Login</a>
    </form>
    
    <div class="form-footer">
        By registering, you agree to our Terms and Privacy Policy
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        var expertiseField = document.getElementById('expertiseField');
        if (this.value === 'doctor') {
            expertiseField.style.display = 'block';
        } else {
            expertiseField.style.display = 'none';
        }
    });
    
    // Add animation to form elements
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.form-control, .form-select');
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>