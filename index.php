 <!DOCTYPE html>
<html>
<body>

<?php

$servername = "localhost";
$username = "smoothie";
$password = "geradeZahl246-bplaced";

	$servername = "localhost";
	$user = "smoothie";
	$password = "geradeZahl246-db";
    $dbname = "smoothie_db";

    echo "test2";
    $conn = pg_connect("host=localhost dbname=$dbname user=$user password=$password");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Verbindung erfolgreich hergestellt";
    }
    // CREATE TABLE unit (id SERIAL PRIMARY KEY, name VARCHAR(20) NOT NULL);
    $tableUnit = "INSERT INTO unit(name) VALUES ('g'), ('ml'), ('Stück');";
    // pg_query($tableUnit);

    $query = "SELECT * FROM unit";
    $result = pg_query($query);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["name"]. "<br>";
        }
    } else {
        echo "0 results";
    }
	?>
</body>
</html>
