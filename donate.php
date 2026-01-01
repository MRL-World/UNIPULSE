<?php
session_start();
include 'db.php';
$success_msg = ""; $error_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $blood_group = $conn->real_escape_string($_POST['blood_group']);
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    $dept = $conn->real_escape_string($_POST['department']);
    $sql = "INSERT INTO donor_details (donor_name, blood_group, student_id, contact_number, address, department) 
            VALUES ('$name', '$blood_group', '$student_id', '$contact', '$address', '$dept')";
    if ($conn->query($sql) === TRUE) {
        $success_msg = "Thank you! You are now registered as a donor.";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Blood - UniPulse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .donate-section { padding: 80px 0; min-height: 100vh; background: #f8fafc; display: flex; align-items: center; justify-content: center; }
        .donate-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--secondary); }
        .form-control { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.1); }
        .btn-submit { width: 100%; padding: 14px; font-size: 1.1rem; font-weight: 600; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer; transition: background 0.3s; }
        .btn-submit:hover { background: #be123c; }
        .page-title { text-align: center; margin-bottom: 30px; font-size: 2rem; color: var(--secondary); }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <section class="donate-section">
        <div class="donate-card">
            <h2 class="page-title"> One donation, countless smiles ❤️</h2>
            <?php if ($success_msg): ?><div class="alert alert-success"><?= $success_msg ?></div><?php endif; ?>
            <?php if ($error_msg): ?><div class="alert alert-error"><?= $error_msg ?></div><?php endif; ?>
            <form method="POST" action="">
                <div class="form-group"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" required placeholder="Ex: Md. Muntasir Rahman"></div>
                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div><label class="form-label">Blood Group</label><select name="blood_group" class="form-control" required><option value="">Select</option><option value="A+">A+</option><option value="A-">A-</option><option value="B+">B+</option><option value="B-">B-</option><option value="AB+">AB+</option><option value="AB-">AB-</option><option value="O+">O+</option><option value="O-">O-</option></select></div>
                    <div><label class="form-label">Student ID</label><input type="text" name="student_id" class="form-control" required placeholder="university id "></div>
                </div>
                <div class="form-group"><label class="form-label">Department</label><input type="text" name="department" class="form-control" required placeholder="Ex: CSE, EEE"></div>
                <div class="form-group"><label class="form-label">Contact Number</label><input type="tel" name="contact" class="form-control" required placeholder="Ex: 01712345678"></div>
                <div class="form-group"><label class="form-label">Present Address</label><textarea name="address" class="form-control" rows="3" required placeholder="Ex: Hall Name or Area"></textarea></div>
                <button type="submit" class="btn-submit">Register as Donor</button>
            </form>
        </div>
    </section>
    <?php include 'includes/footer.php'; ?>
</body>
</html>