<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="main-header">
    <div class="container header-content">
        <a href="<?= isset($_SESSION['user_id']) ? 'index.php' : 'login.php' ?>" class="logo">
            <span class="logo-icon">🩸</span>
            <span class="logo-text">Uni<span class="text-gradient">Pulse</span></span>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" class="nav-link">Home</a></li>
                    <li><a href="inventory.php" class="nav-link">Inventory</a></li>
                    <li><a href="donate.php" class="nav-link">Donate Blood</a></li>
                    <li><a href="request.php" class="nav-link">Request Blood</a></li>
                    <li><a href="profile.php" class="nav-link">Profile</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <span style="font-weight: 600; color: var(--secondary);">Hi,
                    <?= htmlspecialchars($_SESSION['fullname']) ?></span>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            </div>
        <?php else: ?>
            <nav class="main-nav">
                <!-- Show minimal or no nav for guests -->
            </nav>
            <div class="header-actions">
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="btn btn-primary">Donate Now</a>
            </div>
        <?php endif; ?>
    </div>
</header>
<style>
    .main-header {
        height: var(--header-height);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
    }
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    .logo {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--secondary);
    }
    .logo-icon {
        font-size: 1.8rem;
    }
    .main-nav ul {
        display: flex;
        gap: 32px;
    }
    .nav-link {
        font-weight: 500;
        color: var(--text-main);
        position: relative;
    }
    .nav-link:hover {
        color: var(--primary);
    }
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--primary);
        transition: width 0.3s ease;
    }
    .nav-link:hover::after {
        width: 100%;
    }
    .header-actions {
        display: flex;
        align-items: center;
        gap: 24px;
    }
</style>