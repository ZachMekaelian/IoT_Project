<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .alarm {
            background-color: #ff0000;
            color: #fff;
        }

        .pagination {
            margin: 20px 0;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            border: 1px solid #ddd;
            text-decoration: none;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        function fetchData() {
            $.ajax({
                url: 'fetch_data.php?page=' + currentPage,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Clear the table body
                    $('#dashboardTableBody').empty();

                    // Add new rows
                    $.each(data, function(index, row) {
                        $('#dashboardTableBody').append(`
                            <tr>
                                <td>${row.UserID}</td>
                                <td class="header-value">${row.Header}</td>
                                <td>${row.TransmitterID}</td>
                                <td>${row.Pressure}</td>
                                <td>${row.Temperature}</td>
                                <td>${row.BatteryVoltage}</td>
                                <td>${row.RSSI}</td>
                            </tr>
                        `);
                    });
                    updateHeaderColor();
                }
            });
        }

        function updateHeaderColor() {
            $('.header-value').each(function() {
                if ($(this).text() === 'Alarm') {
                    $(this).addClass('alarm');
                }
            });
        }

        var currentPage = 1;
        // Fetch data initially
        fetchData();

        // Refresh data every 10 seconds
        setInterval(fetchData, 10000);
    });
    </script>
</head>
<body>
    <h1>Dashboard</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Header</th>
                <th>Transmitter ID</th>
                <th>Pressure</th>
                <th>Temperature</th>
                <th>Battery</th>
                <th>RSSI (mV)</th>
            </tr>
        </thead>
        <tbody id="dashboardTableBody">
            <!-- Table will be populated via JavaScript -->
        </tbody>
    </table>

    <!-- Page Navigation -->
    <div class="pagination" id="pagination">
        <!-- This will be populated dynamically -->
    </div>
</body>
</html>
