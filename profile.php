
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - UniPulse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="page-content">
        <div class="container">
            <div class="profile-header glass-card">
                <div class="profile-cover"></div>
                <div class="profile-info">
                    <div class="avatar">JD</div>
                    <div class="info-text">
                        <h2>John Doe</h2>
                        <p>Computer Science Student • Donor since 2023</p>
                        <div class="badges">
                            <span class="badge-pill">A+ Blood</span>
                            <span class="badge-pill star">★ 4.8 Rating</span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <button class="btn btn-primary">Edit Profile</button>
                    </div>
                </div>
            </div>
            <div class="profile-grid">
                <!-- Donation History -->
                <div class="history-section">
                    <h3>Donation History</h3>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="date-box">
                                <span class="month">OCT</span>
                                <span class="day">12</span>
                            </div>
                            <div class="content">
                                <h4>Whole Blood Donation</h4>
                                <p>University Health Center • 450ml</p>
                                <span class="status completed">Completed</span>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="date-box">
                                <span class="month">JUN</span>
                                <span class="day">05</span>
                            </div>
                            <div class="content">
                                <h4>Emergency Response</h4>
                                <p>City Hospital • 450ml</p>
                                <span class="status completed">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Health Stats -->
                <div class="stats-sidebar">
                    <div class="stat-card glass-card">
                        <h3>Next Eligibility</h3>
                        <div class="feature-val">Jan 12, 2026</div>
                        <p class="text-sm">90 days after last donation</p>
                    </div>
                    
                    <div class="stat-card glass-card">
                        <h3>Lives Impacted</h3>
                        <div class="feature-val">6</div>
                        <p class="text-sm">You're a hero!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
<style>
.page-content { padding: 40px 0; }
.profile-header {
    overflow: hidden;
    margin-bottom: 40px;
}
.profile-cover {
    height: 150px;
    background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
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
    grid-template-columns: 2fr 1fr;
    gap: 40px;
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
    }
    .profile-actions button {
        width: 100%;
    }
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
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
}
.date-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #F1F5F9;
    padding: 12px;
    border-radius: var(--radius-md);
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
    padding: 24px;
    margin-bottom: 24px;
}
.feature-val {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    margin: 8px 0;
}
</style>
</body>
</html>
