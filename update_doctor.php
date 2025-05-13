<?php
include 'config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$doctor_id = $_GET['id'];
$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $dob = $_POST['dob'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $expertise = $conn->real_escape_string($_POST['expertise']);

    $conn->begin_transaction();

    $sql_user = "UPDATE user SET name = '$name', phone_number = '$phone_number', dob = '$dob', password = '$password' WHERE id = '$doctor_id' AND role = 'doctor'";
    $update_user = $conn->query($sql_user);

    $sql_doctor = "UPDATE doctor SET expertise = '$expertise' WHERE user_id = '$doctor_id'";
    $update_doctor = $conn->query($sql_doctor);

    if ($update_user && $update_doctor) {
        $conn->commit();
        $message = "Doctor updated successfully.";
        $messageType = "success";
    } else {
        $conn->rollback();
        $message = "Error updating doctor: " . ($conn->error);
        $messageType = "error";
    }
}

$sql = "SELECT user.name, user.phone_number, user.dob, doctor.expertise FROM user
        JOIN doctor ON user.id = doctor.user_id WHERE user.id = '$doctor_id' AND user.role = 'doctor'";
$result = $conn->query($sql);
$doctor = $result->fetch_assoc();

if (!$doctor) {
    echo "<div style='padding: 20px; background: #dc3545; color: white; text-align: center;'>Doctor not found.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Doctor | SELFhealth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #2b5876, #4e4376);
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glass-card h2 {
            text-align: center;
            font-weight: 700;
            color: #fff;
            margin-bottom: 2rem;
            text-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .form-group label {
            color: #e0e0e0;
            font-weight: 500;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            transition: 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
            border: 1px solid #ffc107;
        }

        .btn-update {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #000;
            font-weight: 600;
            padding: 0.85rem;
            border-radius: 12px;
            border: none;
            width: 100%;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .btn-update:hover {
            background: linear-gradient(135deg, #ffb300, #ffa000);
            transform: scale(1.03);
        }

        .alert-container .alert {
            border-radius: 10px;
            padding: 1rem;
        }

        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: 0.3s;
        }

        .back-button:hover {
            background: linear-gradient(135deg, #0056b3, #003f7f);
        }
    </style>
</head>
<body>

<a href="admin_dashboard.php" class="back-button"><i class="fas fa-arrow-left me-1"></i> Back</a>

<div class="glass-card">
    <h2><i class="fas fa-user-md me-2"></i>Update Doctor</h2>

    <?php if ($message): ?>
        <div class="alert-container">
            <div class="alert alert-<?= $messageType == 'success' ? 'success' : 'danger' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group mb-3">
            <label for="name"><i class="fas fa-user me-1"></i> Name</label>
            <input type="text" class="form-control" name="name" id="name" value="<?= htmlspecialchars($doctor['name']) ?>" placeholder="Enter full name" required>
        </div>

        <div class="form-group mb-3">
            <label for="phone_number"><i class="fas fa-phone me-1"></i> Phone Number</label>
            <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?= htmlspecialchars($doctor['phone_number']) ?>" placeholder="e.g., +255..." required>
        </div>

        <div class="form-group mb-3">
            <label for="dob"><i class="fas fa-calendar-alt me-1"></i> Date of Birth</label>
            <input type="date" class="form-control" name="dob" id="dob" value="<?= htmlspecialchars($doctor['dob']) ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="password"><i class="fas fa-lock me-1"></i> New Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter new password" required>
            <small class="text-light">Enter new password to update credentials.</small>
        </div>

        <div class="form-group mb-4">
            <label for="expertise"><i class="fas fa-stethoscope me-1"></i> Expertise</label>
            <select class="form-control" name="expertise" id="expertise" required>
                <?php foreach (['HIV', 'Malaria', 'Typhoid', 'UTI'] as $option): ?>
                    <option value="<?= $option ?>" <?= $doctor['expertise'] == $option ? 'selected' : '' ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-update"><i class="fas fa-save me-2"></i>Update Doctor</button>
    </form>
</div>

</body>
</html>
