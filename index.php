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

// Query to fetch patient data
$sql = "SELECT * FROM patient_data";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'patient_id' => $row['patient_id'],
            'symptom_score' => $row['symptom_score'],
            'visit_date' => $row['visit_date']
        ];
    }
}

// Close the database connection
$conn->close();

// Convert data to JSON format
$json_data = json_encode($data);

// Path to the Python interpreter and script
$python_path = 'C:\\xampp\\htdocs\\ai\\myenv\\Scripts\\python.exe';
$script_path = 'c:\\xampp\\htdocs\\ai\\anomaly_detection.py';

// Command to execute the Python script
$command = "$python_path $script_path";

// Execute the Python script
$process = proc_open($command, [
    0 => ['pipe', 'r'],  // stdin
    1 => ['pipe', 'w'],  // stdout
    2 => ['pipe', 'w']   // stderr
], $pipes);

// Write input data to Python script
fwrite($pipes[0], $json_data);
fclose($pipes[0]);

// Read the output from the Python script
$output = stream_get_contents($pipes[1]);
fclose($pipes[1]);

// Read errors (if any) from the Python script
$errors = stream_get_contents($pipes[2]);
fclose($pipes[2]);

// Close the process
$return_value = proc_close($process);

$anomalies = [];
if ($return_value === 0) {
    $anomalies = json_decode($output, true);
} else {
    echo "Error: " . $errors;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .anomaly { background-color: #fdd; }
    </style>
</head>
<body>
    <h1>Clinic Dashboard</h1>
    <?php if (isset($anomalies)) : ?>
        <h2>Anomaly Detection Results:</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Patient ID</th>
                <th>Symptom Score</th>
                <th>Visit Date</th>
                <th>Anomaly</th>
            </tr>
            <?php foreach ($anomalies as $record) : ?>
                <tr class="<?= $record['anomaly'] == -1 ? 'anomaly' : '' ?>">
                    <td><?= htmlspecialchars($record['id']) ?></td>
                    <td><?= htmlspecialchars($record['patient_id']) ?></td>
                    <td><?= htmlspecialchars($record['symptom_score']) ?></td>
                    <td><?= htmlspecialchars($record['visit_date']) ?></td>
                    <td><?= $record['anomaly'] == -1 ? 'Anomaly' : 'Normal' ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No anomaly data available.</p>
    <?php endif; ?>
</body>
</html>
