<?php
include 'config.php';
session_start();

if ($_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the patient's current information from the database
$sql = "SELECT * FROM user WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $phone_number = $row['phone_number'];
    $dob = $row['dob'];
    $gender = $row['gender'];
    $password = ''; // Password will be handled separately
} else {
    echo "Error: User not found.";
    exit;
}

$message = "";  // Variable to store messages
$messageType = ""; // To store message type (success, error)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET name='$name', phone_number='$phone_number', dob='$dob', gender='$gender', password='$hashed_password' WHERE id='$user_id'";
    } else {
        $sql = "UPDATE user SET name='$name', phone_number='$phone_number', dob='$dob', gender='$gender' WHERE id='$user_id'";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "Your information has been updated successfully.";
        $messageType = "success";
    } else {
        $message = "There was an error updating your information: " . $conn->error;
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient Information - SELFhealth</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00897b; /* Teal */
            --primary-dark: #00695c;
            --primary-light: #b2dfdb;
            --accent-color: #ffc107; /* Amber */
            --text-dark: #333;
            --text-light: #fff;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            background: url('update_bg.jpg') no-repeat center center fixed; /* Replace with an appropriate image */
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            background-color: rgba(var(--primary-color-rgb), 0.9);
            color: var(--text-light);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            margin: 0;
            font-size: 1.8rem;
        }

        .header .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .header .btn-back {
            background-color: var(--accent-color);
            color: var(--text-dark);
        }

        .header .btn:hover {
            opacity: 0.85;
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4rem 2rem;
        }

        .update-container {
            background-color: rgba(var(--bg-light-rgb), 0.95);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .update-container h3 {
            color: var(--primary-color);
            text-align: center;
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: var(--text-dark);
        }

        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="date"],
        .form-group select {
            width: calc(100% - 12px);
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn-update {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .btn-update:hover {
            background-color: var(--primary-dark);
        }

        .alert-success, .alert-error {
            padding: 0.8rem 1.5rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            text-align: center;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>

<div class="header">
    <h2><i class="fas fa-user-edit me-2"></i> Update Information</h2>
    <a href="patient_dashboard.php" class="btn btn-back"><i class="fas fa-arrow-left me-2"></i> Back</a>
</div>

<div class="content">
    <div class="update-container">
        <h3><i class="fas fa-id-card-alt me-2"></i> Update Your Details</h3>
        <?php if ($message): ?>
            <div class="alert-<?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <form method="post" action="updateP.php">
            <div class="form-group">
                <label for="name"><i class="fas fa-user me-1"></i> Name:</label>
                <input type="text" class="form-control" name="name" id="name" value="<?= htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number"><i class="fas fa-phone me-1"></i> Phone Number:</label>
                <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?= htmlspecialchars($phone_number); ?>" required>
            </div>
            <div class="form-group">
                <label for="dob"><i class="fas fa-calendar-alt me-1"></i> Date of Birth:</label>
                <input type="date" class="form-control" name="dob" id="dob" value="<?= htmlspecialchars($dob); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender"><i class="fas fa-venus-mars me-1"></i> Gender:</label>
                <select class="form-control" name="gender" id="gender" required>
                    <option value="male" <?= $gender == 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?= $gender == 'female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-key me-1"></i> New Password (leave empty if not changing):</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <button type="submit" class="btn btn-update"><i class="fas fa-save me-2"></i> Update Information</button>
        </form>
    </div>
</div>

</body>
</html>