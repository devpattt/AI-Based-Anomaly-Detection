<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Query to fetch patient data
$sql = "SELECT * FROM patient_data";
$result = $conn->query($sql);

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Clinic Dashboard</h1>
    <?php
    if ($result->num_rows > 0) {
        // Output data for anomaly detection
        echo "<table border='1'>
        <tr>
            <th>ID</th>
            <th>Patient ID</th>
            <th>Symptom Score</th>
            <th>Visit Date</th>
        </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"]. "</td>
                <td>" . $row["patient_id"]. "</td>
                <td>" . $row["symptom_score"]. "</td>
                <td>" . $row["visit_date"]. "</td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
