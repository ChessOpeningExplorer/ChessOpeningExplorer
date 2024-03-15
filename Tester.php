<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chess Opening Explorer</title>
    <style>

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            cursor: pointer;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        function sortTable(column) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("openingsTable");
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[column];
                    y = rows[i + 1].getElementsByTagName("TD")[column];

                    if (isNaN(x.innerHTML)) {
                        shouldSwitch = x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase();
                    } else {
                        shouldSwitch = parseInt(x.innerHTML) > parseInt(y.innerHTML);
                    }

                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        break;
                    }
                }
            }
        }
    </script>
</head>
<body>

<h1>Chess Opening Explorer</h1>

<form method="post" action="">
    <label for="eloFilter">Filter by Elo Rating:</label>
    <input type="number" name="eloFilter" id="eloFilter" min="900" max="2000" placeholder="Enter Elo Rating">
    <input type="submit" value="Filter">
</form>

<?php


$conn = mysqli_connect("localhost:3306", "root", "", "chessdb1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default query without Elo rating filter
$query = "SELECT * FROM openings";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the Elo rating from the form
    $eloFilter = isset($_POST['eloFilter']) ? $_POST['eloFilter'] : null;

    // Validate Elo rating (you may add more validation as needed)
    if (!empty($eloFilter) && is_numeric($eloFilter) && $eloFilter >= 900 && $eloFilter <= 2000) {
        // Order by the absolute difference between Elo ratings
        $query = "SELECT *  FROM openings ORDER BY ABS (Elo - $eloFilter)";
    }
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table id='openingsTable'>";
    echo "<tr><th onclick='sortTable(0)'>Opening Name</th><th onclick='sortTable(1)'>Elo Rating</th><th onclick='sortTable(2)'>Open or Close</th><th onclick='sortTable(3)'>Move Sequence</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><a href='subpage.php?opening=" . urlencode($row["Nombre"]) . "'>" . $row["Nombre"] . "</a></td>";
        echo "<td>" . $row["Elo"] . "</td>";
        echo "<td>" . $row["cl_op"] . "</td>";
        echo "<td>" . $row["MvSeq"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No openings found in the database.";
}

?>
</body>
</html>