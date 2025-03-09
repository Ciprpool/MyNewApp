<?php
session_start(); // Start session for user authentication

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "my_actual_database";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch users
$users = $conn->query("SELECT id, email, role FROM users");

// Fetch emergency alerts
$alerts = $conn->query("SELECT id, alert_message, status, created_at, type FROM emergency_alerts ORDER BY created_at DESC");

// Fetch reports
$reports = $conn->query("SELECT id, user_id, report_type, details, created_at FROM reports ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .header { background: #333; color: white; padding: 15px; text-align: center; position: relative; }
        .logout-btn { position: absolute; right: 20px; top: 10px; padding: 10px 15px; background-color: #dc3545; color: white; border: none; cursor: pointer; border-radius: 3px; }
        .logout-btn:hover { background-color: #c82333; }
        .container { padding: 20px; }
        .card { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; padding: 10px; text-align: left; }
        button { padding: 5px 10px; background-color: #28a745; color: white; border: none; cursor: pointer; border-radius: 3px; }
        button:hover { background-color: #218838; }
    </style>
    <script>
        function updateRole(userId) {
            if (confirm("Are you sure you want to make this user an admin?")) {
                fetch('update_role.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'user_id=' + userId
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Reload the page to reflect changes
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
    </div>
    <div class="container">
        <div class="card">
            <h2>User Management</h2>
            <table>
                <tr><th>ID</th><th>Email</th><th>Role</th><th>Action</th></tr>
                <?php while ($user = $users->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin') { ?>
                                <button onclick="updateRole(<?php echo $user['id']; ?>)">Add as Admin</button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="card">
            <h2>Emergency Alerts</h2>
            <table>
                <tr><th>ID</th><th>Message</th><th>Status</th><th>Type</th><th>Created At</th></tr>
                <?php while ($alert = $alerts->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $alert['id']; ?></td>
                        <td><?php echo $alert['alert_message']; ?></td>
                        <td><?php echo $alert['status']; ?></td>
                        <td><?php echo $alert['type']; ?></td>
                        <td><?php echo $alert['created_at']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="card">
            <h2>Reports</h2>
            <table>
                <tr><th>ID</th><th>User ID</th><th>Report Type</th><th>Details</th><th>Created At</th></tr>
                <?php while ($report = $reports->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $report['id']; ?></td>
                        <td><?php echo $report['user_id']; ?></td>
                        <td><?php echo $report['report_type']; ?></td>
                        <td><?php echo $report['details']; ?></td>
                        <td><?php echo $report['created_at']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
