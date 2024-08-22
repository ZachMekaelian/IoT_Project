<?php
$dbPath = "IoT_Data.db";
$conn = new SQLite3($dbPath);

// Delete all data from the Data table
$query = "DELETE FROM Data";
$conn->exec($query);
echo "Data table has been cleared!<br>";

// Query the Data table to retrieve all data
$query = "SELECT * FROM Data";
$result = $conn->query($query);

// Check and display the data
echo "Listing all rows in the Data table:<br>";
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "<pre>";
    print_r($row);
    echo "</pre>";
}

$conn->close();
?>
