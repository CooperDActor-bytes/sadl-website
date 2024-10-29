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

// Retrieve metrics
$sql = "SELECT * FROM metrics ORDER BY collected_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'><tr><th>ID</th><th>OS Info</th><th>CPU Info</th><th>Memory Info</th><th>Disk Info</th><th>Uptime</th><th>Network Info</th><th>Collected At</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["os_info"]."</td><td>".$row["cpu_info"]."</td><td>".$row["mem_info"]."</td><td>".$row["disk_info"]."</td><td>".$row["uptime_info"]."</td><td>".$row["network_info"]."</td><td>".$row["collected_at"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "No metrics available";
}

$conn->close();
?>