<?php
session_start();
include 'db.php';
// If already logged in, redirect to index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim email to remove extra spaces
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Corrected: Check against 'password_hash' column (as per register.php)
        if ($password == $row['password_hash']) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['fullname'] = $row['fullname'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found with this email!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UniPulse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            /* Using the new Glass Background */
            background: url('glass_bg.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Outfit', sans-serif;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.85); /* Slightly more opaque for readability */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .auth-logo { font-size: 3rem; margin-bottom: 20px; display: block; }
        .auth-title { font-size: 1.8rem; color: #1e293b; margin-bottom: 8px; font-weight: 800; }
        .auth-subtitle { color: #64748b; margin-bottom: 32px; font-size: 0.95rem; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #334155; font-size: 0.9rem; }
        .form-control {
            width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px;
            font-size: 1rem; transition: all 0.3s; background: rgba(255,255,255,0.9);
        }
        .form-control:focus { outline: none; border-color: #E11D48; box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1); }
        .btn-auth {
            width: 100%; padding: 14px; background: #E11D48; color: white; border: none;
            border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer;
            transition: background 0.3s; margin-top: 10px;
        }
        .btn-auth:hover { background: #be123c; }
        .auth-footer { margin-top: 24px; font-size: 0.9rem; color: #64748b; }
        .auth-footer a { color: #E11D48; text-decoration: none; font-weight: 600; }
        .auth-footer a:hover { text-decoration: underline; }
        .error-msg { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="auth-card">
        <span class="auth-logo">🩸</span>
        <h2 class="auth-title">Welcome Back</h2>
        <p class="auth-subtitle">Sign in to continue saving lives.</p>
        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-auth">Sign In</button>
        </form>
        <div class="auth-footer">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>