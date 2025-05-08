<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "l1j_remastered";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get column information for npc table
$sql = "SHOW COLUMNS FROM npc";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>NPC Table Structure:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["Field"] . "</td>";
        echo "<td>" . $row["Type"] . "</td>";
        echo "<td>" . $row["Null"] . "</td>";
        echo "<td>" . $row["Key"] . "</td>";
        echo "<td>" . $row["Default"] . "</td>";
        echo "<td>" . $row["Extra"] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No columns found in npc table.";
}

// Check if table exists
$sql = "SHOW TABLES LIKE 'npc'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<p>NPC table exists.</p>";
} else {
    echo "<p>NPC table does not exist.</p>";
}

$conn->close();
?>
