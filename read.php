<?php
// Connect to the SQLite database
$database = new SQLite3('IoT_Data.db');

// Fetch data from the Users table
$usersQuery = "SELECT * FROM Users";
$usersResult = $database->query($usersQuery);

// Fetch data from the Data table
$dataQuery = "SELECT * FROM Data";
$dataResult = $database->query($dataQuery);

echo "<h2>Users Table</h2>";
echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Password</th> <!-- This should ideally not be displayed for security reasons -->
        </tr>";
while ($row = $usersResult->fetchArray(SQLITE3_ASSOC)) {
    echo "<tr>
            <td>{$row['ID']}</td>
            <td>{$row['First_Name']}</td>
            <td>{$row['Last_Name']}</td>
            <td>{$row['Username']}</td>
            <td>{$row['Password']}</td> <!-- This should ideally not be displayed for security reasons -->
          </tr>";
}
echo "</table>";

echo "<h2>Data Table</h2>";
echo "<table border='1'>
        <tr>
            <th>UserID</th>
            <th>Header</th>
            <th>TransmitterID</th>
            <th>Pressure</th>
            <th>Temperature</th>
            <th>BatteryVoltage</th>
            <th>RSSI</th>
        </tr>";
while ($row = $dataResult->fetchArray(SQLITE3_ASSOC)) {
    echo "<tr>
            <td>{$row['UserID']}</td>
            <td>{$row['Header']}</td>
            <td>{$row['TransmitterID']}</td>
            <td>{$row['Pressure']}</td>
            <td>{$row['Temperature']}</td>
            <td>{$row['BatteryVoltage']}</td>
            <td>{$row['RSSI']}</td>
          </tr>";
}
echo "</table>";

$database->close();
?>
