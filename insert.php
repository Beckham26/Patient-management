<?php
include 'config.php'; 

$name = 'Ghost';
$usernameID = 'A100';
$password = password_hash('123', PASSWORD_BCRYPT); 
$role = 'admin';
$phone_number = '6544'; 
$dob = '1980-01-01'; 
$gender = 'male'; 

$sql = "INSERT INTO user (name, dob, gender, usernameID, password, role, confirmed, phone_number) 
        VALUES (?, ?, ?, ?, ?, ?, FALSE, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $dob, $gender, $usernameID, $password, $role, $phone_number);

if ($stmt->execute()) {
    echo "Admin account created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
