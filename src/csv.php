<?php
    require '../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable('./');
    // $dotenv = Dotenv\Dotenv::createImmutable( _DIR_ . '/');
    $dotenv->load();


    $host = $_ENV['DB_HOST'];
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $dbname = $_ENV['DB_NAME'];

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connectie mislukt: " . $conn->connect_error);
}

$query = "SELECT StudentNaam, StudentAchternaam, Studentmail, StudentNummer FROM studentinfo";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $csvData = [];

    while ($row = $result->fetch_assoc()) {
        $csvData[] = [$row['StudentNaam'], $row['StudentAchternaam'], $row['Studentmail'], $row['StudentNummer']];
    }


    
    $csv = League\Csv\Writer::createFromString('');
    $csv->insertOne(['voornaam', 'achternaam', 'email', 'telefoonnummer']);
    $csv->insertAll($csvData);


    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="studentenlijst.csv"');


    echo $csv;
} else {
    echo "Geen studenten gevonden.";
}

$conn->close();