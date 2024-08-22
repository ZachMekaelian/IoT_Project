<?php
// Database connection settings
$dbPath = "IoT_Data.db"; // Path to your SQLite database file
$conn = new SQLite3($dbPath);

// Get total entries
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM Data");
$result = $stmt->execute();
$row = $result->fetchArray();
$total_entries = $row['total'];

// Close the database connection
$conn->close();

// Return total entries
echo json_encode(array('total_entries' => $total_entries));
?>
