<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$dbPath = "IoT_Data.db"; // Path to your SQLite database file

// Create a new SQLite database connection 
$conn = new SQLite3($dbPath);

// Function to parse and insert data into the data table asdfasdf
function insertData($conn, $transmitterID, $pressure, $temperature, $battery, $rssi, $header, $user_id) {
    $stmt = $conn->prepare("INSERT INTO Data (UserID, Header, TransmitterID, Pressure, Temperature, BatteryVoltage, RSSI) VALUES (:user_id, :header, :transmitterID, :pressure, :temperature, :battery, :rssi)");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':header', $header, SQLITE3_INTEGER);
    $stmt->bindValue(':transmitterID', $transmitterID, SQLITE3_INTEGER);
    $stmt->bindValue(':pressure', $pressure, SQLITE3_INTEGER);
    $stmt->bindValue(':temperature', $temperature, SQLITE3_INTEGER);
    $stmt->bindValue(':battery', $battery, SQLITE3_INTEGER);
    $stmt->bindValue(':rssi', $rssi, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        echo "Data inserted successfully!";
    } else {
        echo "Database Error: " . $conn->lastErrorMsg();
    }
}

$data = file_get_contents('php://input');
file_put_contents('logs.txt', date('Y-m-d H:i:s') . " - Received Data: " . $data . "\n", FILE_APPEND);

if (empty($data)) {
    echo "No data received!";
    exit;
}

// Convert the received JSON data to an associative array
$fileContents = json_decode($data, true);

if (isset($fileContents[0]['data'])) {
    // Loop through all received data
    foreach ($fileContents as $dataItem) {
        // Decode the base64 data from the "body" field
        $base64Data = $dataItem['data']['body'];
        $decodedData = base64_decode($base64Data);

        // Log the decoded data to logs.txt
        file_put_contents('logs.txt', "Decoded Data: " . $decodedData . "\n", FILE_APPEND);

        // Now you can access $decodedData for further processing

        // Parse the data format: "[40 06 00 00 B1 00 79 91 75 = 831 76]"
        $matches = [];
        preg_match('/\[(.*?) = (\d+) (\d+)\]/', $decodedData, $matches);

        if (count($matches) === 4) {
            $segments = explode(' ', $matches[1]);

            $header = hexdec($segments[0]); 
            $transmitterID = hexdec(implode('', array_slice($segments, 1, 4)));
            $pressure = hexdec($segments[5]);
            $temperature = hexdec($segments[6]); 

            // Calculate battery voltage based on the second to last hex value
            $batteryHex = $segments[7];
            if ($batteryHex <= '64') {
                $battery = 100;
            } elseif ($batteryHex >= '65' && $batteryHex <= '80') {
                $battery = 200;
            } elseif ($batteryHex >= '81' && $batteryHex <= '90') {
                $battery = 300;
            } elseif ($batteryHex >= '91' && $batteryHex <= '96') {
                $battery = 400;
            } else {
                $battery = 0; // Set a default value or handle the case as per your requirement
            }

            $rssi = hexdec($segments[8]);

            // Insert data into the Data table
            insertData($conn, $transmitterID, $pressure, $temperature, $battery, $rssi, $header, 1); // Change 1 to the appropriate user_id

            if ($conn->lastErrorCode()) {
                echo "Database Error: " . $conn->lastErrorMsg();
            } else {
                echo "Data inserted successfully!";
            }
        } else {
            echo "Invalid data format.";
        }
    }
} else {
    echo "No 'data' field found in the received JSON.";
    exit;
}

$conn->close();
?>
