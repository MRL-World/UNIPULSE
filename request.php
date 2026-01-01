<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
$message = '';
// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ম্যানুয়াল নাম ও ফোন নেওয়া হচ্ছে
    $requester_name = $conn->real_escape_string($_POST['requester_name']);
    $requester_phone = $conn->real_escape_string($_POST['requester_phone']);
    
    $urgency = $_POST['urgency'];
    $blood_type = $_POST['blood_type'];
    $units = (int) $_POST['units'];
    $hospital = $conn->real_escape_string($_POST['hospital']);
    $notes = $conn->real_escape_string($_POST['notes']);
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO blood_requests (id, user_id, requester_name, requester_phone, urgency, blood_type, units, hospital, notes, status, created_at) 
            VALUES (NULL, '$user_id', '$requester_name', '$requester_phone', '$urgency', '$blood_type', '$units', '$hospital', '$notes', 'active', NOW())";
    if ($conn->query($sql) === TRUE) {
        $message = '<div class="alert success">Request submitted successfully!</div>';
    } else {
        $message = '<div class="alert error">Database Error: ' . $conn->error . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood - UniPulse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .page-content {
            padding: 64px 0;
        }
        .page-header {
            text-align: center;
            margin-bottom: 48px;
        }
        .page-header p {
            font-size: 1.2rem;
            color: var(--text-muted);
        }
        /* GRID LAYOUT FIX */
        .request-grid {
            display: grid;
            grid-template-columns: 1.8fr 1fr; /* Form takes nearly 2/3, Sidebar 1/3 */
            gap: 40px;
            align-items: start;
        }
        @media (max-width: 991px) {
            .request-grid {
                grid-template-columns: 1fr; /* Stack on smaller screens */
            }
        }
        .request-form-container {
            padding: 40px;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            margin-top: 8px;
        }
        .form-row {
            display: flex;
            gap: 24px;
            margin-top: 24px;
        }
        .form-row .form-group {
            flex: 1;
        }
        .urgency-selector {
            display: flex;
            gap: 16px;
            margin-top: 12px;
        }
        .radio-card {
            flex: 1;
            cursor: pointer;
        }
        .radio-card input {
            display: none;
        }
        .card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 16px;
            border: 2px solid var(--border-light);
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
        }
        .icon {
            font-size: 1.5rem;
        }
        .radio-card input:checked+.card-content {
            border-color: var(--primary);
            background: rgba(225, 29, 72, 0.05);
        }
        .mt-4 { margin-top: 24px; }
        .mt-6 { margin-top: 32px; }
        /* Active Requests List */
        .requests-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 24px;
        }
        .request-item {
            background: white;
            padding: 16px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid var(--border-light);
        }
        .req-badge {
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 600;
        }
        .req-badge.critical { background: #FECDD3; color: #BE123C; }
        .req-badge.urgent { background: #FFEDD5; color: #C2410C; }
        .req-info h4 { font-size: 1rem; margin-bottom: 4px; }
        .req-info p { font-size: 0.85rem; color: var(--text-muted); }
        /* MODAL STYLES */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-card {
            background: white; padding: 32px; width: 90%; max-width: 500px;
            border-radius: 16px; position: relative;
        }
        .close-modal {
            position: absolute; top: 16px; right: 24px; font-size: 28px; cursor: pointer; color: #666;
        }
        .modal-body { margin-top: 24px; display: flex; flex-direction: column; gap: 12px; }
        .modal-row { display: flex; justify-content: space-between; align-items: center; }
        .text-highlight { color: var(--primary); font-weight: bold; font-size: 1.2em; }
        .btn-xs { background: var(--primary); color: white; padding: 2px 8px; border-radius: 4px; text-decoration: none; font-size: 0.8rem; }
        hr { border: 0; border-top: 1px solid #eee; margin: 8px 0; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <h1>Blood Request Portal</h1>
                <p>Submit a blood request manually properly.</p>
                <?= $message ?>
            </div>
            <div class="request-grid">
                <!-- LEFTSIDE: FORM -->
                <div class="request-form-container glass-card">
                    <h3>New Request</h3>
                    <form action="request.php" method="POST">
                        
                        <!-- New Manual Fields -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="requester_name">Requester Name</label>
                                <input type="text" id="requester_name" name="requester_name" placeholder="Enter name" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="requester_phone">Contact Phone</label>
                                <input type="tel" id="requester_phone" name="requester_phone" placeholder="017..." required class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-4 mt-4">
                            <label>Urgency Level</label>
                            <div class="urgency-selector">
                                <label class="radio-card">
                                    <input type="radio" name="urgency" value="routine" checked>
                                    <span class="card-content">
                                        <span class="icon">📋</span>
                                        <span>Routine</span>
                                    </span>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="urgency" value="urgent">
                                    <span class="card-content">
                                        <span class="icon">⚠️</span>
                                        <span>Urgent</span>
                                    </span>
                                </label>
                                <label class="radio-card critical">
                                    <input type="radio" name="urgency" value="critical">
                                    <span class="card-content">
                                        <span class="icon">🚨</span>
                                        <span>Critical</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="blood_type">Blood Type Needed</label>
                                <select id="blood_type" name="blood_type" class="form-control">
                                    <option>A+</option>
                                    <option>B+</option>
                                    <option>O+</option>
                                    <option>AB+</option>
                                    <option>A-</option>
                                    <option>B-</option>
                                    <option>O-</option>
                                    <option>AB-</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="units">Units Required</label>
                                <input type="number" id="units" name="units" min="1" value="1" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <label for="hospital">Clinic / Hospital Name</label>
                            <input type="text" id="hospital" name="hospital" class="form-control"
                                placeholder="e.g., University Health Center">
                        </div>
                        <div class="form-group mt-4">
                            <label for="notes">Additional Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="form-control"
                                placeholder="Patient details or  patient type "></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-6">Submit Request</button>
                    </form>
                </div>

                
                <!-- RIGHTSIDE: ACTIVE REQUESTS -->
                <div class="active-requests">
                    <h3>Active Requests</h3>
                    <div class="requests-list">
                        <?php
                        // Fetching plain data (including new manual columns)
                        $sql = "SELECT * FROM blood_requests WHERE status='active' ORDER BY created_at DESC";
                        $result = $conn->query($sql);
                        if (!$result) {
                            echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
                        } elseif ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $timeAgo = time() - strtotime($row['created_at']);
                                if ($timeAgo < 60) { $posted = "Just now"; }
                                elseif ($timeAgo < 3600) { $posted = floor($timeAgo/60) . " mins ago"; }
                                elseif ($timeAgo < 86400) { $posted = floor($timeAgo/3600) . " hours ago"; }
                                else { $posted = floor($timeAgo/86400) . " days ago"; }
                                $fullTime = date("F j, Y, g:i a", strtotime($row['created_at']));
                                // Using the MANUALLY entered name and phone
                                $rName = isset($row['requester_name']) ? htmlspecialchars($row['requester_name']) : '';
                                $rPhone = isset($row['requester_phone']) ? htmlspecialchars($row['requester_phone']) : '';
                                echo "
                                <div class='request-item'>
                                    <div class='req-badge {$row['urgency']}'>" . ucfirst($row['urgency']) . "</div>
                                    <div class='req-info'>
                                        <h4>{$row['blood_type']} Needed ({$row['units']} Units)</h4>
                                        <p>{$row['hospital']} • {$posted}</p>
                                    </div>
                                    <button class='btn-sm btn-outline' onclick='openModal(this)'
                                        data-blood='{$row['blood_type']}'
                                        data-units='{$row['units']}'
                                        data-hospital='{$row['hospital']}'
                                        data-notes='{$row['notes']}'
                                        data-requester='{$rName}'
                                        data-phone='{$rPhone}'
                                        data-time='{$fullTime}'>
                                        View
                                    </button>
                                </div>";
                            }
                        } else {
                            echo "<p style='color: var(--text-muted); text-align: center;'>No active requests.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Request Details Modal -->
    <div id="requestModal" class="modal-overlay">
        <div class="modal-card glass-card">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h2>Request Details</h2>
            <div class="modal-body">
                <div class="modal-row">
                    <strong>Blood Type:</strong> <span id="m-blood" class="text-highlight"></span>
                </div>
                <div class="modal-row">
                    <strong>Units Required:</strong> <span id="m-units"></span>
                </div>
                <hr>
                <div class="modal-row">
                    <strong>Hospital:</strong> <span id="m-hospital"></span>
                </div>
                <div class="modal-row">
                    <strong>Notes:</strong> <span id="m-notes" style="font-style: italic;"></span>
                </div>
                <hr>
                <div class="modal-row">
                    <strong>Requester Name:</strong> <span id="m-requester"></span>
                </div>
                <div class="modal-row">
                    <strong>Phone:</strong> <span id="m-phone"></span> <a href="#" id="call-btn" class="btn-xs">Call</a>
                </div>
                <div class="modal-row">
                    <strong>Posted At:</strong> <span id="m-time" style="color: grey; font-size: 0.9em;"></span>
                </div>
                <button class="btn btn-primary btn-block mt-4" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
    <!-- Modal Scripts -->
    <script>
        function openModal(btn) {
            document.getElementById('m-blood').textContent = btn.getAttribute('data-blood');
            document.getElementById('m-units').textContent = btn.getAttribute('data-units');
            document.getElementById('m-hospital').textContent = btn.getAttribute('data-hospital');
            document.getElementById('m-notes').textContent = btn.getAttribute('data-notes') || 'None';
            document.getElementById('m-requester').textContent = btn.getAttribute('data-requester');
            document.getElementById('m-phone').textContent = btn.getAttribute('data-phone');
            document.getElementById('m-time').textContent = btn.getAttribute('data-time');
            
            document.getElementById('call-btn').href = 'tel:' + btn.getAttribute('data-phone');
            document.getElementById('requestModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('requestModal').style.display = 'none';
        }
        window.onclick = function(event) {
            let modal = document.getElementById('requestModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>