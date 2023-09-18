<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studentenlijst</title> 
    
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
<!-- Zoekbalk voor studentenlijst -->
    <input type="text" id="zoekbalk" placeholder="Zoeken...">
    <?php
    // Verbinding maken met de database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "toepassingsopdracht";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['sorteer'])) {
        // Als er is geklikt om te sorteren, bepaal de sorteervolgorde
        $sorteer = $_GET['sorteer'];
        $sql = "SELECT StudentNaam, StudentAchternaam, Studentmail, StudentNummer FROM studentinfo ORDER BY StudentAchternaam $sorteer";
    } else {
        // Als er niet is geklikt om te sorteren, haal alle studenten op zonder specifieke volgorde
        $sql = "SELECT StudentNaam, StudentAchternaam, Studentmail, StudentNummer FROM studentinfo";
    }

    // Query uitvoeren
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Weergeven van de studentinformatie in een kaart op de webpagina
            echo "<div class='kaart'>" . $row["StudentNaam"] . "<div class='details'>
                <p class='telefoonnummer'>Telefoonnummer: " . $row["StudentNummer"] . "</p>
                <p class='email'>Email: " . $row["Studentmail"] . "</p>
            </div></div>";
        }
    } else {
        echo "Geen resultaten gevonden.";
    }

    // Databaseverbinding sluiten
    $conn->close();
    ?>
</div>
<script>
    // JavaScript-code voor interactie met de webpagina

    // Zoekfunctionaliteit
    const zoekbalk = document.getElementById("zoekbalk");
    const kaarten = document.querySelectorAll(".kaart");
    const alleKaarten = Array.from(kaarten);
    let zoekterm = "";

    zoekbalk.addEventListener("input", () => {
        zoekterm = zoekbalk.value.toLowerCase();

        alleKaarten.forEach((kaart) => {
            const kaartNaam = kaart.textContent.toLowerCase();
            const details = kaart.querySelector(".details");

            if (kaartNaam.includes(zoekterm) || details.textContent.toLowerCase().includes(zoekterm)) {
                kaart.style.display = "block";
            } else {
                kaart.style.display = "none";
            }
        });
    });

    // Uitklappen van studentdetails
    kaarten.forEach((kaart) => {
        kaart.addEventListener("click", () => {
            const details = kaart.querySelector(".details");
            details.classList.toggle("zichtbaar");
            if (zoekterm && !details.textContent.toLowerCase().includes(zoekterm)) {
                kaart.style.display = "none";
            }
        });
    });

    // Sorteerfunctie
    function sorteerKaarten() {
        const huidigeURL = new URL(window.location.href);
        const sorteer = huidigeURL.searchParams.get("sorteer") || "ASC";
        const nieuweSorteer = sorteer === "ASC" ? "DESC" : "ASC";
        huidigeURL.searchParams.set("sorteer", nieuweSorteer);
        window.location.href = huidigeURL;
    }

    // Knop voor het sorteren van studenten
    const sorteerKnop = document.createElement("button");
    sorteerKnop.type = "button";
    sorteerKnop.textContent = "Sorteer";
    sorteerKnop.id = "sorteerKnop";
    sorteerKnop.addEventListener("click", sorteerKaarten);
    document.body.appendChild(sorteerKnop);
</script>
</body>
</html>
