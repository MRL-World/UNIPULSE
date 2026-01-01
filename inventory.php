<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
include 'db.php';
// Fetch donors
$donors_by_bg = [];
$d_sql = "SELECT * FROM donor_details ORDER BY created_at DESC";
$d_result = $conn->query($d_sql);
if ($d_result && $d_result->num_rows > 0) {
    while($d = $d_result->fetch_assoc()) { $donors_by_bg[$d['blood_group']][] = $d; }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Inventory - UniPulse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .inventory-section { padding: 100px 0; background: url('inventory_bg.png') no-repeat center center/cover; background-attachment: fixed; position: relative; min-height: 100vh; }
        .inventory-section::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.75); z-index: 1; }
        .inventory-section .container { position: relative; z-index: 2; }
        .section-header { text-align: center; margin-bottom: 64px; }
        .section-header h2 { color: white; text-shadow: 0 4px 10px rgba(0,0,0,0.5); font-size: 3.5rem; margin-bottom: 16px; font-weight: 800; }
        .section-header p { color: rgba(255,255,255,0.9); font-size: 1.3rem; margin: 0 auto; }
        .inventory-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 32px; }
        .blood-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.15); padding: 32px; display: flex; flex-direction: column; align-items: center; gap: 20px; transition: all 0.4s; cursor: pointer; box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3); border-radius: 24px; }
        .blood-card:hover { transform: translateY(-10px) scale(1.02); background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.4); box-shadow: 0 20px 40px -10px rgba(225, 29, 72, 0.5); }
        .blood-type { font-size: 3rem; font-weight: 800; color: white; background: linear-gradient(135deg, var(--primary), #FF5F6D); width: 90px; height: 90px; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 8px 25px rgba(225, 29, 72, 0.5); }
        .blood-level { width: 100%; display: flex; align-items: center; gap: 12px; }
        .level-bar { flex-grow: 1; height: 10px; background: rgba(255, 255, 255, 0.15); border-radius: var(--radius-full); overflow: hidden; }
        .level-fill { height: 100%; border-radius: var(--radius-full); box-shadow: 0 0 15px currentColor; }
        .inventory-section span { color: white; font-weight: 700; }
        .blood-status { font-size: 0.9rem; font-weight: 700; padding: 6px 16px; border-radius: var(--radius-full); text-transform: uppercase; }
        .critical { color: #FF4D4D; background: rgba(255, 77, 77, 0.1); border: 1px solid rgba(255, 77, 77, 0.4); }
        .low { color: #FB923C; background: rgba(251, 146, 60, 0.1); border: 1px solid rgba(251, 146, 60, 0.4); }
        .good { color: #2DD4BF; background: rgba(45, 212, 191, 0.1); border: 1px solid rgba(45, 212, 191, 0.4); }
        
        /* Modal */
        .modal { display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.8); backdrop-filter: blur(5px); align-items: center; justify-content: center; }
        .modal-content { background-color: #fff; margin: auto; border-radius: 20px; width: 90%; max-width: 600px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); overflow: hidden; }
        .modal-header { padding: 24px; background: var(--primary); color: white; display: flex; justify-content: space-between; align-items: center; }
        .close { color: white; font-size: 2rem; cursor: pointer; }
        .modal-body { padding: 24px; max-height: 60vh; overflow-y: auto; }
        .donor-item { display: flex; gap: 16px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 12px; }
        .d-avatar { width: 50px; height: 50px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .btn-call { padding: 8px 16px; background: #22c55e; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; margin-left: auto; display:flex; align-items:center; }
    </style>
</head>


<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <section class="inventory-section">
            <div class="container">
                <div class="section-header"><h2>Live Blood Inventory</h2><p>Click on any blood group to view available donors.</p></div>
                <div class="inventory-grid">
                    <?php
                    $sql = "SELECT * FROM blood_inventory";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $bg = $row['blood_type'];
                            $status = strtolower($row['status']);
                            $cls = ($status=='critical')?'critical':(($status=='low')?'low':(($status=='moderate')?'moderate':'good'));
                            $count = isset($donors_by_bg[$bg]) ? count($donors_by_bg[$bg]) : 0;
                            echo "<div class='blood-card' onclick=\"openDonorModal('$bg')\">
                                <div class='blood-type'>$bg</div>
                                <div class='blood-level'><div class='level-bar'><div class='level-fill $cls' style='width: {$row['units_available']}%'></div></div><span>{$row['units_available']}%</span></div>
                                <div style='display:flex; justify-content:space-between; width:100%; align-items:center;'><span style='font-size:0.85rem; opacity:0.8;'>$count Donors</span><div class='blood-status $cls'>{$row['status']}</div></div>
                            </div>";
                        }
                    } else { echo "<p style='color: white; text-align:center;'>No data available.</p>"; }
                    ?>
                </div>
            </div>
        </section>
    </main>
    <div id="donorModal" class="modal"><div class="modal-content"><div class="modal-header"><h2 id="modalTitle"></h2><span class="close" onclick="document.getElementById('donorModal').style.display='none'">&times;</span></div><div class="modal-body" id="modalBody"></div></div></div>
    <script>
        const donors = <?php echo json_encode($donors_by_bg); ?>;
        function openDonorModal(bg) {
            const body = document.getElementById('modalBody');
            document.getElementById('modalTitle').textContent = `Available Donors (${bg})`;
            body.innerHTML = '';
            if (donors[bg] && donors[bg].length > 0) {
                donors[bg].forEach(d => {
                    body.innerHTML += `<div class="donor-item"><div class="d-avatar">👤</div><div class="d-info"><h4>${d.donor_name}</h4><p>Dept: ${d.department}</p><p>ID: ${d.student_id}</p><p>${d.address}</p></div><a href="tel:${d.contact_number}" class="btn-call">📞 Call</a></div>`;
                });
            } else { body.innerHTML = '<p style="text-align:center; color:#64748b; padding:20px;">No registered donors found.</p>'; }
            document.getElementById('donorModal').style.display = 'flex';
        }
    </script>
    <?php include 'includes/footer.php'; ?>
</body>
</html>