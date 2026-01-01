<?php
// Database credentials
$host = '127.0.0.1'; // Mac-এ localhost এর বদলে 127.0.0.1 ভালো কাজ করে
$user = 'root';      // XAMPP ডিফল্ট ইউজার
$pass = '';          // XAMPP ডিফল্ট পাসওয়ার্ড (ফাঁকা থাকে)
$db   = 'unipulse_db';
// Create connection
$conn = new mysqli($host, $user, $pass, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
    mysqli_select_db($conn, $db);
    // echo "Connected successfully";
}
?>