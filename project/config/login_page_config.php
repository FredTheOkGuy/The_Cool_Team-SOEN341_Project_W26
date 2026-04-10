<?php
// Dynamically detect BASE_URL from the current script path
function get_base_url() {
    $script_name = $_SERVER['SCRIPT_NAME']; // e.g. //The_Cool_Team-.../project/login.php
    
    // Find the position of '/project/' in the path and keep everything up to and including it
    $project_pos = strpos($script_name, '/project/');
    if ($project_pos !== false) {
        return substr($script_name, 0, $project_pos + strlen('/project'));
    }
    
    // Fallback: strip the filename from the script path
    return rtrim(dirname($script_name), '/');
}

define('BASE_URL', get_base_url());
// Simple config file (connects to the database), don't know why it's called login_page_config, can be used everywhere
$host = "localhost";
$user = "root";
$password = "";   // empty for XAMPP on Mac usually
$database = "users_db"; // or whatever database name you created in phpMyAdmin

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
