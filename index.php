<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniPulse - Campus Blood Donor Database</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Modern Glass Theme */
        body {
            /* Using the new 'Glass Cells' background */
            background: url('glass_bg.png') no-repeat center center fixed;
            background-size: cover; min-height: 100vh; font-family: 'Outfit', sans-serif;
        }
        .home-main { position: relative; min-height: calc(100vh - 80px); display: flex; align-items: center; overflow: hidden; padding: 40px 0; }
        .container { width: 90%; max-width: 1200px; margin: 0 auto; position: relative; z-index: 2; }
        .hero-layout { display: grid; grid-template-columns: 1.2fr 1fr; gap: 60px; align-items: center; }
        .hero-content { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(10px); padding: 40px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.6); box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .badge-pill { display: inline-flex; align-items: center; padding: 8px 16px; background: rgba(225, 29, 72, 0.1); color: #E11D48; border-radius: 100px; font-weight: 600; font-size: 0.9rem; margin-bottom: 24px; border: 1px solid rgba(225, 29, 72, 0.1); }
        .hero-title { font-size: 3.5rem; line-height: 1.1; margin-bottom: 24px; color: #1e293b; letter-spacing: -1px; font-weight: 800; }
        .hero-title span { color: #E11D48; }
        .hero-text { font-size: 1.15rem; color: #475569; margin-bottom: 40px; line-height: 1.6; }
        .btn-group { display: flex; gap: 16px; }
        .btn-primary { background: #E11D48; color: white; padding: 14px 30px; border-radius: 100px; text-decoration: none; font-weight: 600; border: none; box-shadow: 0 8px 20px rgba(225, 29, 72, 0.3); transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(225, 29, 72, 0.4); }
        .btn-outline { background: transparent; color: #E11D48; padding: 12px 28px; border-radius: 100px; text-decoration: none; font-weight: 600; border: 2px solid #E11D48; transition: 0.3s; }
        .btn-outline:hover { background: #E11D48; color: white; }
        
        /* Glass Card for Feed */
        .glass-card { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); border-radius: 24px; padding: 30px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
        .feed-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 16px; }
        .feed-item { background: rgba(255,255,255,0.8); border-radius: 16px; padding: 16px; margin-bottom: 16px; display: flex; align-items: center; gap: 16px; transition: all 0.3s; border: 1px solid transparent; }
        .feed-item:hover { transform: translateX(-5px); background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-color: #E11D48; }
        .blood-type-circle { width: 45px; height: 45px; background: #E11D48; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; box-shadow: 0 5px 15px rgba(225, 29, 72, 0.3); }
        @media (max-width: 900px) { .hero-layout { grid-template-columns: 1fr; text-align: center; } .hero-content { padding: 30px; margin-bottom: 40px; } .btn-group { justify-content: center; } }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="home-main">
        <div class="container">
            <div class="hero-layout">
                <div class="hero-content">
                    <div class="badge-pill">Join the UniPulse family and help save lives 🩸</div>
                    <h1 class="hero-title">Every Drop Creates <br><span>Ripple of Hope</span></h1>
                    <p class="hero-text">UniPulse connects donors with those in need instantly. Join our community and make a difference today.</p>
                    <div class="btn-group"><a href="request.php" class="btn-primary">Request Blood</a><a href="donate.php" class="btn-outline">Donate Blood</a></div>
                </div>
                <div class="glass-card">
                    <div class="feed-header"><h3>🔴 Live Requests</h3><a href="request.php" style="color: #E11D48; text-decoration: none; font-size: 0.9rem; font-weight: 600;">View All &rarr;</a></div>
                    <div class="feed-list">
                        <?php
                        $sql = "SELECT br.*, u.fullname FROM blood_requests br JOIN users u ON br.user_id = u.user_id WHERE br.status='active' ORDER BY created_at DESC LIMIT 3";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='feed-item'><div class='blood-type-circle'>{$row['blood_type']}</div><div style='flex:1; text-align:left;'><h4 style='margin:0; font-size:1rem; color:#1e293b;'>{$row['hospital']}</h4><p style='margin:4px 0 0; font-size:0.85rem; color:#64748b;'>{$row['units']} Units • " . ucfirst($row['urgency']) . "</p></div></div>";
                            }
                        } else { echo "<div style='text-align:center; padding:30px; color:#94a3b8;'><p>No active requests found.</p></div>"; }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>