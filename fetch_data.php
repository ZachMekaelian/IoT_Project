<?php
header('Content-Type: application/json');

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 100; // Number of rows per page
$offset = ($page - 1) * $limit; // Calculate the offset

$dbPath = "IoT_Data.db";
$conn = new SQLite3($dbPath);

$stmt = $conn->prepare("SELECT UserID, Header, TransmitterID, Pressure, Temperature, BatteryVoltage, RSSI FROM Data LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
$stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
$result = $stmt->execute();

$data = [];

if ($result) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $row['Header'] = $row['Header'] ? 'Alarm' : 'Normal'; // Convert header value to text
        $row['Pressure'] .= ' kPa';
        $row['Temperature'] .= ' &deg;C';
        $row['BatteryVoltage'] .= ' mV';
        $row['RSSI'] .= ' mV';
        $data[] = $row;
    }
} else {
    file_put_contents('logs.txt', date('Y-m-d H:i:s') . " - SQL Error: " . $conn->lastErrorMsg() . "\n", FILE_APPEND);
}

echo json_encode($data);

$conn->close();
?>
