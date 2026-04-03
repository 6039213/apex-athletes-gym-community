<?php
// Database Import Script - DELETE AFTER USE!
$db_host = 'localhost';
$db_user = 'st1738846938';
$db_pass = 'R5IFHm9dw7k6W6r';
$db_name = 'st1738846938';
$sql_file = 'Apex_Athletes_export.sql';

if (!file_exists($sql_file)) {
    die("SQL file not found: $sql_file");
}

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Connected to database...<br>";
echo "Importing SQL file...<br>";

$sql = file_get_contents($sql_file);
$queries = explode(';', $sql);

$success = 0;
$errors = 0;

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if ($mysqli->query($query)) {
            $success++;
        } else {
            $errors++;
        }
    }
}

echo "Import complete!<br>";
echo "Successful queries: $success<br>";
echo "Errors: $errors<br>";
echo "<br><strong>DELETE THIS FILE NOW!</strong>";

$mysqli->close();
?>
