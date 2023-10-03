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


use Dompdf\Dompdf;
use Dompdf\Options;


$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);


$dompdf = new Dompdf($options);


$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Studentenlijst PDF</title>
    <style>

      
        
        table{
            border-collapse: collapse;

        }
        th, td{
            border: 1px solid black;
        }
        

        }
        th, td {
            text-align: center;
            padding: 8px;
        }
                
         

    </style>
</head>
<body>
    <h1>Studentenlijst: WEB2A</h1>
    <table>
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Telefoonnummer</th>
            </tr>
';
       

$query = "SELECT * FROM studentinfo";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['StudentNaam'] . ' ' . $row['StudentAchternaam'] . '</td>';
        $html .= '<td>' . $row['Studentmail'] . '</td>';
        $html .= '<td>' . $row['StudentNummer'] . '</td>';
        $html .= '</tr>';
    }
}

$html .= '
    </table>
</body>
</html>';

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$dompdf->stream("studentenlijst.pdf");

$conn->close();
?>