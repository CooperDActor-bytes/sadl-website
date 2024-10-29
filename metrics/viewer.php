<?php
function get_db_credentials() {
    $auth_url = "https://cloudexis.net/services/auth.php";
    $auth_response = file_get_contents($auth_url);
    return json_decode($auth_response, true);
}

$db_credentials = get_db_credentials();
$servername = $db_credentials['host'];
$username = $db_credentials['username'];
$password = $db_credentials['password'];
$dbname = $db_credentials['dbname'];


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve metrics (only the required fields)
$sql = "SELECT id, os_type, hostname, cpu_type, total_ram, ip_address, collected_at FROM metrics ORDER BY collected_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Display table headers for selected columns
    echo "<table border='1'><tr><th>ID</th><th>Hostname</th><th>IP Address</th><th>OS Type</th><th>CPU Type</th><th>Total RAM</th><th>Collected At</th></tr>";
    
    // Display each row with the selected metrics
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['hostname']}</td>
                <td>{$row['ip_address']}</td>
                <td>{$row['os_type']}</td>
                <td>{$row['cpu_type']}</td>
                <td>{$row['total_ram']}</td>
                <td>{$row['collected_at']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No metrics available";
}


$conn->close();
?>