<footer class="main-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="logo">
                    <span class="logo-icon">🩸</span>
                    <span class="logo-text"><span class="text-gradient">UniPulse</span></span>
                </div>
                <p>Connecting campus community for a lifesaving cause. Real-time blood donor database and emergency
                    network.</p>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Find a Donor</a></li>
                    <li><a href="#">Request Blood</a></li>
                    <li><a href="#">Inventory</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Faculty</a></li>
                    <li><a href="#">Emergency</a></li>
                </ul>
            </div>
            <div class="footer-newsletter">
                <h4>Stay Updated</h4>
                <div class="input-group">
                    <input type="email" placeholder="Enter your email">
                    <button class="btn btn-primary">Subscribe</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 UniPulse Campus Database. All rights reserved.</p>
        </div>
    </div>
</footer>
<style>
    .main-footer {
        background: var(--secondary);
        color: white;
        padding: 64px 0 32px;
        margin-top: 64px;
    }
    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.5fr;
        gap: 48px;
        margin-bottom: 48px;
    }
    .footer-brand p {
        color: #94A3B8;
        margin-top: 16px;
        max-width: 300px;
    }
    .footer-links h4,
    .footer-newsletter h4 {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 24px;
    }
    .footer-links ul {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .footer-links a {
        color: #94A3B8;
    }
    .footer-links a:hover {
        color: var(--primary);
        padding-left: 4px;
    }
    .input-group {
        display: flex;
        gap: 8px;
    }
    .input-group input {
        padding: 12px;
        border-radius: var(--radius-full);
        border: none;
        outline: none;
        width: 100%;
    }
    .footer-bottom {
        border-top: 1px solid #1E293B;
        padding-top: 32px;
        text-align: center;
        color: #94A3B8;
    }
</style>