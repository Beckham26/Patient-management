<?php
include 'config.php';
session_start();

if ($_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT name FROM user WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $patient_name = $row['name'];
} else {
    $patient_name = "Unknown Patient";
}

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $disease_id = $_POST['disease_id'];
    $progress = $conn->real_escape_string($_POST['progress']);

    $sql = "INSERT INTO submission (patient_id, disease_id, progress) VALUES ('$user_id', '$disease_id', '$progress')";

    if ($conn->query($sql) === TRUE) {
        $message = "Your progress has been submitted successfully.";
        $messageType = "success";
    } else {
        $message = "There was an error submitting your progress: " . $conn->error;
        $messageType = "error";
    }
}

$sql = "SELECT * FROM disease";
$diseases = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - SELFhealth</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-dark: #1b5e20;
            --primary-light: #c8e6c9;
            --accent-color: #ffb300;
            --text-dark: #333;
            --text-light: #fff;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            background: url('dashboard_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-heading {
            text-align: center;
            padding: 2rem 1rem 1rem;
            background-color: rgba(255, 255, 255, 0.8);
            margin-top: 60px;
        }

        .animated-heading {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            animation: fadeInZoom 2s ease-in-out forwards;
        }

        .animated-heading span {
            color: var(--accent-color);
            animation: colorShift 3s infinite alternate;
        }

        @keyframes fadeInZoom {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes colorShift {
            0% { color: var(--accent-color); }
            100% { color: #ff7043; }
        }

        .welcome-banner {
            background-color: rgba(255, 179, 0, 0.8);
            color: var(--text-dark);
            padding: 1rem 2rem;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .animated-words {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
        }

        .animated-words span {
            animation: slideIn 10s linear infinite;
            padding-left: 100%;
        }

        @keyframes slideIn {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }

        .header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            background-color: rgba(46, 125, 50, 0.9);
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

        .header .patient-info {
            display: flex;
            align-items: center;
        }

        .header .patient-name {
            margin-right: 1.5rem;
            font-size: 1rem;
            font-weight: bold;
        }

        .header .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .header .btn-update {
            background-color: var(--accent-color);
            color: var(--text-dark);
        }

        .header .btn-logout {
            background-color: #dc3545;
            color: var(--text-light);
            margin-left: 0.5rem;
        }

        .header .btn:hover {
            opacity: 0.85;
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        .dashboard-container {
            background-color: rgba(248, 249, 250, 0.9);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
        }

        .form-section, .submissions-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background-color: var(--text-light);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-section h3, .submissions-section h3 {
            color: var(--primary-color);
            text-align: center;
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: var(--text-dark);
        }

        .form-group select,
        .form-group textarea {
            width: calc(100% - 12px);
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: var(--primary-dark);
        }

        .submissions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .submissions-table th, .submissions-table td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .submissions-table th {
            background-color: var(--bg-light);
            font-weight: bold;
            color: var(--text-dark);
        }

        .submissions-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .submissions-table tr:hover {
            background-color: #f5f5f5;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 0.8rem 1.5rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }

        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 0.8rem 1.5rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Patient Dashboard</h2>
    <div class="patient-info">
        <span class="patient-name"><i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($patient_name); ?></span>
        <a href="updateP.php" class="btn btn-update"><i class="fas fa-cog me-1"></i> Update Profile</a>
        <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
    </div>
</div>

<div class="main-heading">
    <h1 class="animated-heading">Welcome to <span>SELFhealth</span></h1>
</div>

<div class="welcome-banner">
    <div class="animated-words">
        <span>Welcome to our hospital! Let us know how we can help you. </span>
        <span>Welcome to our hospital! Let us know how we can help you. </span>
        <span>Welcome to our hospital! Let us know how we can help you. </span>
    </div>
</div>

<div class="content">
    <div class="dashboard-container">

        <?php if ($message): ?>
            <div class="alert-<?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="form-section">
            <h3><i class="fas fa-heartbeat me-2"></i> Submit Your Health Progress</h3>
            <form method="post" action="patient_dashboard.php">
                <div class="form-group">
                    <label for="disease_id"><i class="fas fa-thermometer-half me-1"></i> Disease:</label>
                    <select class="form-control" name="disease_id" id="disease_id" required>
                        <?php while ($row = $diseases->fetch_assoc()) { ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="progress"><i class="fas fa-notes-medical me-1"></i> Progress Description:</label>
                    <textarea class="form-control" name="progress" id="progress" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-submit"><i class="fas fa-paper-plane me-1"></i> Submit Progress</button>
            </form>
        </div>

        <div class="submissions-section">
            <h3><i class="fas fa-history me-2"></i> Your Previous Submissions</h3>
            <?php
            $sql = "SELECT submission.*, disease.name AS disease_name FROM submission
                    JOIN disease ON submission.disease_id = disease.id
                    WHERE patient_id='$user_id'
                    ORDER BY submission_date DESC";
            $submissions = $conn->query($sql);

            if ($submissions->num_rows > 0) {
                echo "<table class='submissions-table'>";
                echo "<thead><tr><th>Disease</th><th>Progress</th><th>Doctor's Reply</th><th>Date</th></tr></thead>";
                echo "<tbody>";
                while ($row = $submissions->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['disease_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['progress']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['reply'] ? $row['reply'] : 'No reply yet') . "</td>";
                    echo "<td>" . date("Y-m-d H:i:s", strtotime($row['submission_date'])) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p><i class='fas fa-exclamation-circle me-1'></i> No submissions found yet.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
