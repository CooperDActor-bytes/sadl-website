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

// Collect data from POST request
$os_info = $_POST['os_info'];
$cpu_info = $_POST['cpu_info'];
$mem_info = $_POST['mem_info'];
$disk_info = $_POST['disk_info'];
$uptime_info = $_POST['uptime_info'];
$network_info = $_POST['network_info'];

// Insert into database
$sql = "INSERT INTO metrics (os_info, cpu_info, mem_info, disk_info, uptime_info, network_info) 
        VALUES ('$os_info', '$cpu_info', '$mem_info', '$disk_info', '$uptime_info', '$network_info')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
