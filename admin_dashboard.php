<?php
include 'config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$message = "";
$messageType = "";

// Handle doctor confirmation
if (isset($_POST['confirm_doctor'])) {
    $doctor_id = $_POST['doctor_id'];
    $sql = "UPDATE user SET confirmed = 1 WHERE id = '$doctor_id'";
    if ($conn->query($sql) === TRUE) {
        $message = "Doctor confirmed successfully.";
        $messageType = "success";
    } else {
        $message = "Error confirming doctor: " . $conn->error;
        $messageType = "error";
    }
}

// Handle doctor deletion
if (isset($_POST['delete_doctor'])) {
    $doctor_id = $_POST['doctor_id'];

    $sql = "SELECT * FROM user WHERE id = '$doctor_id' AND role = 'doctor'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $conn->begin_transaction();
        $delete_doctor_info = $conn->query("DELETE FROM doctor WHERE user_id = '$doctor_id'");
        $delete_user = $conn->query("DELETE FROM user WHERE id = '$doctor_id' AND role = 'doctor'");

        if ($delete_doctor_info && $delete_user) {
            $conn->commit();
            $message = "Doctor deleted successfully.";
            $messageType = "success";
        } else {
            $conn->rollback();
            $message = "Error deleting doctor: " . $conn->error;
            $messageType = "error";
        }
    } else {
        $message = "Doctor not found.";
        $messageType = "error";
    }
}

// Handle patient deletion
if (isset($_POST['delete_patient'])) {
    $patient_id = $_POST['patient_id'];

    $sql = "SELECT * FROM user WHERE id = '$patient_id' AND role = 'patient'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $conn->begin_transaction();
        $delete_submissions = $conn->query("DELETE FROM submission WHERE patient_id = '$patient_id'");
        $delete_patient_info = $conn->query("DELETE FROM patient WHERE user_id = '$patient_id'");
        $delete_user = $conn->query("DELETE FROM user WHERE id = '$patient_id' AND role = 'patient'");

        if ($delete_submissions && $delete_patient_info && $delete_user) {
            $conn->commit();
            $message = "Patient deleted successfully.";
            $messageType = "success";
        } else {
            $conn->rollback();
            $message = "Error deleting patient: " . $conn->error;
            $messageType = "error";
        }
    } else {
        $message = "Patient not found.";
        $messageType = "error";
    }
}

// Get unconfirmed doctors
$sql = "SELECT user.id, user.usernameID, user.name, user.phone_number, doctor.expertise FROM user
        JOIN doctor ON user.id = doctor.user_id
        WHERE user.role = 'doctor' AND user.confirmed = 0";
$unconfirmed_doctors = $conn->query($sql);

// Get confirmed doctors
$sql = "SELECT user.id, user.usernameID, user.name, user.phone_number, doctor.expertise FROM user
        JOIN doctor ON user.id = doctor.user_id
        WHERE user.role = 'doctor' AND user.confirmed = 1";
$confirmed_doctors = $conn->query($sql);

// Get all patients
$sql = "SELECT id, usernameID, name, phone_number FROM user WHERE role = 'patient'";
$patients = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SELFhealth</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3f51b5; /* Indigo */
            --primary-dark: #303f9f;
            --primary-light: #c5cae9;
            --accent-color: #e91e63; /* Pink */
            --text-dark: #212121;
            --text-light: #fff;
            --bg-light: #f5f5f5;
            --success-color: #4caf50;
            --error-color: #f44336;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .header .btn-logout {
            background-color: var(--accent-color);
            color: var(--text-light);
            border: none;
            padding: 0.7rem 1.2rem;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .header .btn-logout:hover {
            background-color: #c2185b;
        }

        .container {
            padding: 2rem;
            margin: 2rem auto;
            max-width: 1200px;
            background-color: var(--text-light);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        th, td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-light);
            font-weight: bold;
            color: var(--primary-dark);
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #eee;
        }

        .action-buttons form {
            display: inline;
            margin-right: 0.5rem;
        }

        .action-button {
            border: none;
            padding: 0.5rem 0.8rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
            color: var(--text-light);
        }

        .confirm-button {
            background-color: var(--success-color);
        }

        .confirm-button:hover {
            background-color: #43a047;
        }

        .delete-button {
            background-color: var(--error-color);
        }

        .delete-button:hover {
            background-color: #d32f2f;
        }

        .update-link {
            background-color: var(--accent-color);
            color: var(--text-dark);
            padding: 0.5rem 0.8rem;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }

        .update-link:hover {
            background-color: #fbc02d;
        }

        .alert-container {
            margin-bottom: 1.5rem;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 5px;
            font-weight: bold;
        }

        .alert.success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .alert.error {
            background-color: #ffebee;
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        p {
            color: #777;
        }
    </style>
</head>
<body>

<div class="header">
    <h1><i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard</h1>
    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
</div>

<div class="container">
    <?php if ($message): ?>
        <div class="alert-container">
            <div class="alert <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        </div>
    <?php endif; ?>

    <h2><i class="fas fa-user-md me-2"></i> Doctor Management</h2>

    <h3>Unconfirmed Doctors</h3>
    <?php if ($unconfirmed_doctors && $unconfirmed_doctors->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Username ID</th>
                    <th>Name</th>
                    <th>Expertise</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $unconfirmed_doctors->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['usernameID']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['expertise']) ?></td>
                        <td><?= htmlspecialchars($row['phone_number']) ?></td>
                        <td class="action-buttons">
                            <form method="post" action="">
                                <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <button type="submit" name="confirm_doctor" class="action-button confirm-button"><i class="fas fa-check me-1"></i> Confirm</button>
                                <button type="submit" name="delete_doctor" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this doctor?');"><i class="fas fa-trash-alt me-1"></i> Delete</button>
                                <a href="update_doctor.php?id=<?= htmlspecialchars($row['id']) ?>" class="update-link"><i class="fas fa-edit me-1"></i> Update</a>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No doctors awaiting confirmation.</p>
    <?php } ?>

    <h3>Confirmed Doctors</h3>
    <?php if ($confirmed_doctors && $confirmed_doctors->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Username ID</th>
                    <th>Name</th>
                    <th>Expertise</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $confirmed_doctors->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['usernameID']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['expertise']) ?></td>
                        <td><?= htmlspecialchars($row['phone_number']) ?></td>
                        <td class="action-buttons">
                            <form method="post" action="">
                                <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <button type="submit" name="delete_doctor" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this doctor?');"><i class="fas fa-trash-alt me-1"></i> Delete</button>
                                <a href="update_doctor.php?id=<?= htmlspecialchars($row['id']) ?>" class="update-link"><i class="fas fa-edit me-1"></i> Update</a>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No confirmed doctors found.</p>
    <?php } ?>

    <h2><i class="fas fa-users me-2"></i> Patient Management</h2>
    <?php if ($patients && $patients->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Username ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $patients->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['usernameID']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone_number']) ?></td>
                        <td class="action-buttons">
                            <form method="post" action="">
                                <input type="hidden" name="patient_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <button type="submit" name="delete_patient" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this patient?');"><i class="fas fa-trash-alt me-1"></i> Delete</button>
                                <a href="update_patient.php?id=<?= htmlspecialchars($row['id']) ?>" class="update-link"><i class="fas fa-edit me-1"></i> Update</a>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No patients found.</p>
    <?php } ?>
</div>

</body>
</html>