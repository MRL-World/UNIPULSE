<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Donor - UniPulse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    session_start();
    // If logged in, redirect
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
    include 'db.php';
    // Header will skip session_start because we started it
    include 'includes/header.php';
    $message = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = $conn->real_escape_string($_POST['fullname']);
        $email = $conn->real_escape_string($_POST['email']);
        $blood_group = $conn->real_escape_string($_POST['blood_group']);
        $dob = $conn->real_escape_string($_POST['dob']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $password = $_POST['password'];
        // Basic validation
        if (empty($fullname) || empty($email) || empty($password)) {
            $message = '<div class="alert error">Please fill all required fields.</div>';
        } else {
            // পাসওয়ার্ড সরাসরি সেভ করা হচ্ছে (কোনো এনক্রিপশন ছাড়া)
            $hashed_password = $password; 
            $sql = "INSERT INTO users (fullname, email, blood_group, dob, phone, password_hash) 
                VALUES ('$fullname', '$email', '$blood_group', '$dob', '$phone', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                // Auto Login
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['fullname'] = $fullname;
                header("Location: index.php");
                exit();
            } else {
                $message = '<div class="alert error">Error: ' . $conn->error . '</div>';
            }
        }
    }
    ?>
    <main class="auth-page">
        <div class="container">
            <div class="auth-card glass-card">
                <div class="auth-header">
                    <h2>Join the Lifesaving Network</h2>
                    <p>Register as a donor today and make a difference.</p>
                    <?= $message ?>
                </div>
                <form action="#" method="POST" class="auth-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="email">University Email</label>
                            <input type="email" id="email" name="email" placeholder="john@university.edu" required>
                        </div>
                        <div class="form-group">
                            <label for="blood_group">Blood Group</label>
                            <select id="blood_group" name="blood_group" required>
                                <option value="" disabled selected>Select Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Contact Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="+1 234 567 8900" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I agree to the <a href="#">Terms</a> and confirm that I meet the <a
                                href="#">eligibility criteria</a>.</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register Now</button>
                    <p class="auth-footer">Already a donor? <a href="login.php">Login here</a></p>
                </form>
            </div>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <style>
        .auth-page {
            padding: 80px 0;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, #FEE2E2 0%, transparent 40%),
                radial-gradient(circle at bottom left, #CCFBF1 0%, transparent 40%);
        }
        .auth-card {
            max-width: 600px;
            margin: 0 auto;
            padding: 48px;
            background: rgba(255, 255, 255, 0.85);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .auth-header p {
            color: var(--text-muted);
            margin-top: 8px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .form-group label {
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--secondary);
        }
        .form-group input,
        .form-group select {
            padding: 12px 16px;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            background: white;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.1);
        }
        .form-check {
            grid-column: 1 / -1;
            display: flex;
            gap: 12px;
            align-items: center;
            margin: 16px 0 24px;
        }
        .btn-block {
            width: 100%;
            grid-column: 1 / -1;
        }
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
        }
    </style>
</body>
</html>