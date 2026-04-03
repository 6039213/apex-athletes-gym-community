<?php
// Drop existing tables and allow fresh install
$mysqli = new mysqli('localhost', 'st1738846938', 'R5IFHm9dw7k6W6r', 'st1738846938');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Dropping existing tables...<br>";

$tables = ['wp_comments', 'wp_links', 'wp_options', 'wp_commentmeta'];

foreach ($tables as $table) {
    if ($mysqli->query("DROP TABLE IF EXISTS $table")) {
        echo "✓ Dropped $table<br>";
    }
}

echo "<br><strong>Done! Now delete wp-config.php via FTP and reinstall WordPress.</strong><br>";
echo "<strong>DELETE THIS FILE TOO!</strong>";

$mysqli->close();
?>
