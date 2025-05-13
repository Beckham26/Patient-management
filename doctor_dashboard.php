<?php
        include 'config.php';
        session_start();

        if ($_SESSION['role'] != 'doctor') {
            header("Location: index.php");
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // Get the doctor's name and expertise
        $sql = "SELECT u.name, d.expertise
                FROM doctor d
                JOIN user u ON d.user_id = u.id
                WHERE d.user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor_data = $result->fetch_assoc();
        $doctor_name = $doctor_data['name'];
        $expertise = $doctor_data['expertise'];

        // Handle reply submission, update, and delete
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['reply_submit'])) {
                $submission_id = $_POST['submission_id'];
                $reply = $conn->real_escape_string($_POST['reply']);

                $sql = "UPDATE submission SET reply = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $reply, $submission_id);
                if ($stmt->execute()) {
                    $message = "Reply sent successfully.";
                    $messageType = "success";
                } else {
                    $message = "Error: " . $conn->error;
                    $messageType = "error";
                }
            } elseif (isset($_POST['reply_update'])) {
                $submission_id = $_POST['submission_id'];
                $new_reply = $conn->real_escape_string($_POST['new_reply']);

                $sql = "UPDATE submission SET reply = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $new_reply, $submission_id);
                if ($stmt->execute()) {
                    $message = "Reply updated successfully.";
                    $messageType = "success";
                } else {
                    $message = "Error: " . $conn->error;
                    $messageType = "error";
                }
            } elseif (isset($_POST['reply_delete'])) {
                $submission_id = $_POST['submission_id'];

                $sql = "UPDATE submission SET reply = NULL WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $submission_id);
                if ($stmt->execute()) {
                    $message = "Reply deleted successfully.";
                    $messageType = "success";
                } else {
                    $message = "Error: " . $conn->error;
                    $messageType = "error";
                }
            }
        }

        // Retrieve submissions for the doctor based on their expertise
        $sql = "SELECT s.id AS submission_id, s.progress, s.reply, u.name AS patient_name
                FROM submission s
                JOIN patient p ON s.patient_id = p.user_id
                JOIN user u ON p.user_id = u.id
                JOIN disease d ON s.disease_id = d.id
                WHERE d.name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $expertise);
        $stmt->execute();
        $submissions = $stmt->get_result();

        // Separate unreplied and replied submissions
        $unreplied_submissions = [];
        $replied_submissions = [];

        while ($submission = $submissions->fetch_assoc()) {
            if (empty($submission['reply'])) {
                $unreplied_submissions[] = $submission;
            } else {
                $replied_submissions[] = $submission;
            }
        }
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Doctor Dashboard - SELFhealth</title>
            <link rel="icon" href="favicon.ico" type="image/x-icon">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                :root {
                    --primary-color: #3498db; /* Blue */
                    --primary-dark: #2980b9;
                    --primary-light: #e1f5fe;
                    --accent-color: #2ecc71; /* Green */
                    --text-dark: #333;
                    --text-light: #fff;
                    --bg-light: #f4f6f8;
                    --success-color: #27ae60;
                    --error-color: #e74c3c;
                    --card-bg: #fff;
                    --card-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    --border-radius: 8px;
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
                    padding: 1.5rem 2rem;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .header .doctor-name {
                    font-size: 1.8rem;
                    font-weight: 600;
                }

                .header .btn-logout,
                .header .btn-update {
                    background-color: var(--primary-dark);
                    color: var(--text-light);
                    padding: 0.8rem 1.5rem;
                    border: none;
                    border-radius: var(--border-radius);
                    text-decoration: none;
                    font-size: 1rem;
                    transition: background-color 0.3s ease;
                    margin-left: 1rem;
                }

                .header .btn-logout:hover,
                .header .btn-update:hover {
                    background-color: #1a5276;
                }

                .dashboard-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 2rem;
                    padding: 2rem;
                    margin-top: 5rem; /* Adjust for fixed header */
                }

                .card {
                    background-color: var(--card-bg);
                    box-shadow: var(--card-shadow);
                    border-radius: var(--border-radius);
                    padding: 1.5rem;
                }

                .card-title {
                    color: var(--primary-color);
                    font-size: 1.5rem;
                    margin-bottom: 1rem;
                    border-bottom: 2px solid var(--primary-light);
                    padding-bottom: 0.5rem;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th, td {
                    padding: 0.8rem;
                    text-align: left;
                    border-bottom: 1px solid #eee;
                }

                th {
                    background-color: var(--primary-light);
                    color: var(--primary-dark);
                    font-weight: 500;
                }

                .action-buttons {
                    display: flex;
                    gap: 0.5rem;
                }

                .action-button {
                    border: none;
                    color: var(--text-light);
                    cursor: pointer;
                    padding: 0.6rem 1rem;
                    font-size: 0.9rem;
                    border-radius: var(--border-radius);
                    transition: background-color 0.3s ease;
                }

                .btn-reply {
                    background-color: var(--accent-color);
                    color: var(--text-light);
                }

                .btn-reply:hover {
                    background-color: #218838;
                }

                .btn-update {
                    background-color: #f39c12; /* Amber-like */
                    color: var(--text-light);
                }

                .btn-update:hover {
                    background-color: #d87d04;
                }

                .btn-delete {
                    background-color: var(--error-color);
                }

                .btn-delete:hover {
                    background-color: #c0392b;
                }

                /* Modal Styles */
                .modal {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                }

                .modal-content {
                    background-color: var(--card-bg);
                    padding: 2rem;
                    border-radius: var(--border-radius);
                    width: 90%;
                    max-width: 500px;
                    position: relative;
                    box-shadow: var(--card-shadow);
                }

                .modal-content .close {
                    position: absolute;
                    top: 0.5rem;
                    right: 0.5rem;
                    background: none;
                    border: none;
                    color: var(--text-dark);
                    font-size: 1.5rem;
                    cursor: pointer;
                }

                .modal-content h3 {
                    color: var(--primary-color);
                    margin-bottom: 1rem;
                }

                .modal-content textarea {
                    width: calc(100% - 12px);
                    padding: 0.75rem;
                    margin-bottom: 1rem;
                    border: 1px solid #ced4da;
                    border-radius: var(--border-radius);
                    font-size: 1rem;
                }

                .modal-content .btn-submit,
                .modal-content .btn-cancel {
                    border: none;
                    color: var(--text-light);
                    padding: 0.8rem 1.5rem;
                    border-radius: var(--border-radius);
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    font-size: 1rem;
                    margin-top: 0.5rem;
                }

                .modal-content .btn-submit {
                    background-color: var(--accent-color);
                }

                .modal-content .btn-submit:hover {
                    background-color: #218838;
                }

                .modal-content .btn-cancel {
                    background-color: #6c757d;
                }

                .modal-content .btn-cancel:hover {
                    background-color: #5a6268;
                }

                .alert-container {
                    margin: 1rem auto;
                    width: 90%;
                }

                .alert {
                    padding: 1rem 1.5rem;
                    border-radius: var(--border-radius);
                    font-weight: bold;
                    text-align: center;
                }

                .alert.success {
                    background-color: #d4edda;
                    color: var(--success-color);
                    border: 1px solid #c3e6cb;
                }

                .alert.error {
                    background-color: #f8d7da;
                    color: var(--error-color);
                    border: 1px solid #f5c6cb;
                }
            </style>
            <script>
                function openModal(submissionId) {
                    document.getElementById('submission-id-input').value = submissionId;
                    document.getElementById('replyModal').style.display = 'flex';
                }

                function closeModal() {
                    document.getElementById('replyModal').style.display = 'none';
                }
            </script>
        </head>
        <body>

            <div class="header">
                <div class="doctor-name"><i class="fas fa-user-md me-2"></i> Welcome, Dr. <?= htmlspecialchars($doctor_name) ?></div>
                <div>
                    <a href="updateD.php" class="btn-update"><i class="fas fa-edit me-1"></i> Update Profile</a>
                    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                </div>
            </div>

            <div class="dashboard-container">
                <div class="card">
                    <h2 class="card-title">Unreplied Submissions</h2>
                    <?php if (!empty($unreplied_submissions)) { ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Progress</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unreplied_submissions as $submission) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($submission['patient_name']) ?></td>
                                        <td><?= htmlspecialchars($submission['progress']) ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-button btn-reply" onclick="openModal(<?= htmlspecialchars($submission['submission_id']) ?>)"><i class="fas fa-reply me-1"></i> Reply</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>No unreplied submissions.</p>
                    <?php } ?>
                </div>

                <div class="card">
                    <h2 class="card-title">Replied Submissions</h2>
                    <?php if (!empty($replied_submissions)) { ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Progress</th>
                                    <th>Reply Content</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($replied_submissions as $submission) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($submission['patient_name']) ?></td>
                                        <td><?= htmlspecialchars($submission['progress']) ?></td>
                                        <td><?= htmlspecialchars($submission['reply']) ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-button btn-update" onclick="openModal(<?= htmlspecialchars($submission['submission_id']) ?>)"><i class="fas fa-edit me-1"></i> Update</button>
                                                <form method="post" action="" style="display: inline;">
                                                    <input type="hidden" name="submission_id" value="<?= htmlspecialchars($submission['submission_id']) ?>">
                                                    <button type="submit" name="reply_delete" class="action-button btn-delete"><i class="fas fa-trash-alt me-1"></i> Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>No replied submissions.</p>
                    <?php } ?>
                </div>
            </div>

            <div id="replyModal" class="modal">
                <div class="modal-content">
                    <button class="close" onclick="closeModal()">&times;</button>
                    <h3>Reply/Update Submission</h3>
                    <form method="post" action="">
                        <input type="hidden" name="submission_id" id="submission-id-input">
                        <div class="form-group">
                            <label for="new_reply"><i class="fas fa-comment-dots me-1"></i> Your Reply:</label>
                            <textarea id="new_reply" name="new_reply" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="reply_update" class="btn btn-primary me-2"><i class="fas fa-paper-plane me-1"></i> Submit</button>