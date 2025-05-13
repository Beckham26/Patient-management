<?php
include 'config.php';
session_start();

if ($_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch doctor's current data
$sql = "SELECT u.name, u.phone_number, u.dob, u.gender, u.password 
        FROM doctor d 
        JOIN user u ON d.user_id = u.id 
        WHERE d.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    if (empty($password)) {
        $sql = "UPDATE user u
                JOIN doctor d ON u.id = d.user_id
                SET u.name = ?, u.phone_number = ?, u.dob = ?, u.gender = ?
                WHERE u.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $phone_number, $dob, $gender, $user_id);
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE user u
                JOIN doctor d ON u.id = d.user_id
                SET u.name = ?, u.phone_number = ?, u.dob = ?, u.gender = ?, u.password = ?
                WHERE u.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $phone_number, $dob, $gender, $hashed_password, $user_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Information updated successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Information | Doctor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: url('picha.jpg') no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 1000;
        }

        .header .doctor-name {
            font-weight: 600;
            font-size: 20px;
        }

        .header .button-group a {
            margin-left: 10px;
            background: #007bff;
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .header .button-group a.logout-button {
            background: #dc3545;
        }

        .header .button-group a:hover {
            opacity: 0.9;
        }

        .container {
            margin-top: 100px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            width: 90%;
            max-width: 600px;
            color: #fff;
        }

        .container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }

        label {
            margin-top: 15px;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        input[type="submit"] {
            margin-top: 25px;
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: transform 0.2s;
        }

        input[type="submit"]:hover {
            transform: scale(1.03);
        }

        ::placeholder {
            color: #666;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="doctor-name">Doctor Panel</div>
        <div class="button-group">
            <a href="doctor_dashboard.php" class="update-button">Dashboard</a>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Update Your Information</h2>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($doctor_data['name']) ?>" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($doctor_data['phone_number']) ?>" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($doctor_data['dob']) ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male" <?= $doctor_data['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= $doctor_data['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
            </select>

            <label for="password">New Password <small>(leave blank to keep current password)</small>:</label>
            <input type="password" id="password" name="password" placeholder="••••••••">

            <input type="submit" value="Update Information">
        </form>
    </div>

</body>
</html>
