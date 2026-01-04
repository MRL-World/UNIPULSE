<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$message = "";

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $student_id = $conn->real_escape_string($_POST['student_id']);

    $update_sql = "UPDATE users SET fullname='$fullname', phone='$phone', dob='$dob', student_id='$student_id' WHERE user_id='$user_id'";
    if ($conn->query($update_sql)) {
        $_SESSION['fullname'] = $fullname;
        $message = "<div class='alert success'>Profile updated successfully!</div>";
    } else {
        $message = "<div class='alert error'>Update failed: " . $conn->error . "</div>";
    }
}

// Handle Account Deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $delete_sql = "DELETE FROM users WHERE user_id='$user_id'";
    if ($conn->query($delete_sql)) {
        session_destroy();
        header("Location: login.php?deleted=1");
        exit();
    } else {
        $message = "<div class='alert error'>Deletion failed: " . $conn->error . "</div>";
    }
}

// Fetch User Info
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Fetch Donation History from donor_details (linked by Student/Faculty ID)
$sid = $user['student_id'];
$history_sql = "SELECT * FROM donor_details WHERE student_id = '$sid' ORDER BY created_at DESC";
$history_result = $conn->query($history_sql);
$donations = [];
if ($history_result && !empty($sid)) {
    while ($row = $history_result->fetch_assoc()) {
        $donations[] = $row;
    }
}

// Calculate Stats
$count = count($donations);
$last_donation = !empty($donations) ? $donations[0]['created_at'] : null;
$next_eligible = "Ready to Donate";
if ($last_donation) {
    $next_date = date('Y-m-d', strtotime($last_donation . ' + 90 days'));
    if (strtotime($next_date) > time()) {
        $next_eligible = date('M d, Y', strtotime($next_date));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - UniPulse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Custom styles for profile page (Not overriding global layout) */
        .page-content {
            padding: 40px 0;
        }

        .profile-header {
            overflow: hidden;
            margin-bottom: 40px;
        }

        .profile-cover {
            height: 150px;
            background: linear-gradient(135deg, #E11D48 0%, #9f1239 100%);
        }

        .profile-info {
            padding: 0 40px 40px;
            display: flex;
            align-items: flex-end;
            gap: 32px;
            margin-top: -50px;
        }

        .avatar {
            width: 120px;
            height: 120px;
            background: white;
            border: 4px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .info-text {
            flex-grow: 1;
            padding-bottom: 12px;
        }

        .badges {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }

        .badge-pill {
            padding: 4px 12px;
            background: #F1F5F9;
            border-radius: var(--radius-full);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .badge-pill.star {
            background: #FFFBEB;
            color: #B45309;
        }

        .profile-actions {
            padding-bottom: 12px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            gap: 40px;
        }

        .timeline {
            margin-top: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .timeline-item {
            display: flex;
            gap: 24px;
            background: white;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid var(--border-light);
        }

        .date-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #F1F5F9;
            padding: 12px;
            border-radius: 8px;
            min-width: 80px;
        }

        .date-box .month {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
        }

        .date-box .day {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .status.completed {
            color: var(--accent);
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 8px;
        }

        .stat-card {
            padding: 32px;
            margin-bottom: 24px;
        }

        .feature-val {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            margin: 8px 0;
        }

        .text-sm {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        @media (max-width: 900px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .profile-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin-top: -60px;
            }

            .profile-actions {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main class="page-content">
        <div class="container">
            <?= $message ?>
            <div class="profile-header glass-card">
                <div class="profile-cover"></div>
                <div class="profile-info">
                    <div class="avatar">
                        <?php
                        $names = explode(' ', $user['fullname']);
                        $initials = (isset($names[0][0]) ? $names[0][0] : '') . (isset($names[count($names) - 1][0]) ? $names[count($names) - 1][0] : '');
                        echo strtoupper($initials);
                        ?>
                    </div>
                    <div class="info-text">
                        <h2><?= htmlspecialchars($user['fullname']) ?></h2>
                        <p>ID: <?= htmlspecialchars($user['student_id'] ?? 'N/A') ?> • Member since
                            <?= date('Y', strtotime($user['created_at'])) ?>
                        </p>
                        <div class="badges">
                            <span class="badge-pill"><?= htmlspecialchars($user['blood_group'] ?? 'N/A') ?> Blood</span>
                            <span class="badge-pill star">★ 5.0 Rating</span>
                        </div>
                    </div>
                    <div class="profile-actions" style="display: flex; gap: 12px;">
                        <button class="btn btn-primary" onclick="toggleEdit()">Edit Profile</button>
                        <form method="POST" action=""
                            onsubmit="return confirm('WARNING: This will permanently delete your account and all associated data. Are you sure?');">
                            <button type="submit" name="delete_account" class="btn btn-outline"
                                style="border-color: #ef4444; color: #ef4444;">Delete Account</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Form (Hidden by default) -->
            <div id="edit-form-container" class="glass-card" style="display: none; padding: 40px; margin-bottom: 40px;">
                <h3 style="margin-top: 0; margin-bottom: 24px;">Edit Profile Information</h3>
                <form method="POST" action="" class="edit-form">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Student/Faculty ID</label>
                            <input type="text" name="student_id" value="<?= htmlspecialchars($user['student_id']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"
                                class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>"
                                class="form-control">
                        </div>
                    </div>
                    <div style="margin-top: 32px; display: flex; gap: 16px;">
                        <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-outline" onclick="toggleEdit()">Cancel</button>
                    </div>
                </form>
            </div>

            <div class="profile-grid">
                <!-- Donation History -->
                <div class="history-section">
                    <h3>Donation History</h3>
                    <div class="timeline">
                        <?php if (empty($donations)): ?>
                            <div
                                style="color: var(--text-muted); text-align: center; padding: 60px; background: white; border-radius: var(--radius-lg); border: 2px dashed #E2E8F0;">
                                <p style="font-size: 1.1rem; margin-bottom: 12px;">No donations found yet.</p>
                                <a href="donate.php" class="btn btn-outline">Start Donating</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($donations as $donation): ?>
                                <div class="timeline-item">
                                    <div class="date-box">
                                        <span
                                            class="month"><?= strtoupper(date('M', strtotime($donation['created_at']))) ?></span>
                                        <span class="day"><?= date('d', strtotime($donation['created_at'])) ?></span>
                                    </div>
                                    <div class="content">
                                        <h4 style="margin-bottom: 4px;"><?= htmlspecialchars($donation['department']) ?>
                                            Donation</h4>
                                        <p style="color: var(--text-muted); font-size: 0.95rem;">
                                            <?= htmlspecialchars($donation['address']) ?>
                                        </p>
                                        <span class="status completed">✓ Completed</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Health Stats -->
                <div class="stats-sidebar">
                    <div class="stat-card glass-card">
                        <h3 style="font-size: 1.1rem; opacity: 0.8;">Next Eligibility</h3>
                        <div class="feature-val"><?= $next_eligible ?></div>
                        <p class="text-sm">90 days after your last contribution</p>
                    </div>

                    <div class="stat-card glass-card">
                        <h3 style="font-size: 1.1rem; opacity: 0.8;">Lives Impacted</h3>
                        <div class="feature-val"><?= $count ?></div>
                        <p class="text-sm">Thank you for being a hero!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleEdit() {
            const container = document.getElementById('edit-form-container');
            container.style.display = container.style.display === 'none' ? 'block' : 'none';
        }
    </script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>
