<?php
include 'config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$patient_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $dob = $_POST['dob'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "UPDATE user SET name = '$name', phone_number = '$phone_number', dob = '$dob', password = '$password' WHERE id = '$patient_id' AND role = 'patient'";
    if ($conn->query($sql) === TRUE) {
        $message = "Patient updated successfully.";
    } else {
        $message = "Error updating patient: " . $conn->error;
    }
}

// Fetch patient data
$sql = "SELECT name, phone_number, dob FROM user WHERE id = '$patient_id' AND role = 'patient'";
$result = $conn->query($sql);
$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('picha.jpg'); /* Background image */
            background-size: cover; /* Cover the entire page */
            background-position: center; /* Center the image */
            color: #333; /* Default text color */
        }
        .header {
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white */
            padding: 15px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 1000; /* Ensure it's above other content */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .back-button {
            background-color: #003366; /* Dark blue color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
            display: inline-block;
        }
        .header .back-button:hover {
            background-color: #002244; /* Darker shade of dark blue */
        }
        h2 {
            color: #fff; /* Dark blue color */
            text-align: center;
            margin-top: 80px; /* Adjusted for fixed header */
        }
        form {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            max-width: 600px; /* Increased width */
            margin: 30px auto;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Include padding in width calculation */
        }
        input[type="submit"] {
            background-color: #003366; /* Dark blue color */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #002244; /* Darker shade of dark blue */
        }
        p {
            color: #28a745; /* Green color for success messages */
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="admin_dashboard.php" class="back-button">Back</a>
    </div>
    <h2>Update Patient</h2>
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($patient['name']) ?>" required>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" value="<?= htmlspecialchars($patient['phone_number']) ?>" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" value="<?= htmlspecialchars($patient['dob']) ?>" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Update Patient">
    </form>
</body>
</html>
