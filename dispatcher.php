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

function parse_metrics($data) {
    // Parse OS info for hostname and OS type
    $os_parts = explode(" ", $data['os_info']);
    $os_type = $os_parts[0];
    $hostname = $os_parts[1];

    // Parse CPU info for model name
    preg_match("/Model name:\s*(.*)\n/", $data['cpu_info'], $cpu_match);
    $cpu_type = $cpu_match[1] ?? 'Unknown CPU';

    // Parse Memory info for total RAM
    preg_match("/Mem:\s*(\d+\w+)/", $data['mem_info'], $mem_match);
    $total_ram = $mem_match[1] ?? 'Unknown RAM';

    // Parse Network info for IP address (eth0 or primary interface)
    preg_match("/inet\s+([0-9.]+)/", $data['network_info'], $ip_match);
    $ip_address = $ip_match[1] ?? 'Unknown IP';

    return [
        "os_type" => $os_type,
        "hostname" => $hostname,
        "cpu_type" => $cpu_type,
        "total_ram" => $total_ram,
        "ip_address" => $ip_address
    ];
}

// Collect data from POST request and parse
$parsed_data = parse_metrics($_POST);

// Insert parsed data into database
$sql = "INSERT INTO metrics (os_type, hostname, cpu_type, total_ram, ip_address) 
        VALUES ('{$parsed_data['os_type']}', '{$parsed_data['hostname']}', '{$parsed_data['cpu_type']}', '{$parsed_data['total_ram']}', '{$parsed_data['ip_address']}')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
