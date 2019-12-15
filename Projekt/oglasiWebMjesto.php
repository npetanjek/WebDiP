<!DOCTYPE html>
<?php
require './baza.class.php';
require './sesija.class.php';
Sesija::kreirajSesiju();
/* if(!isset($_SESSION["korisnik"])){
  header("Location: prijavaRegistracija.php?mod=prijava");
  } */
if (isset($_GET["mod"]) && $_GET["mod"] == "odjava") {
    $date = date('Y-m-d H:i:s');
    $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Odjava.', '" . $date . "')";
    $veza->updateDB($sql_dnevnik);
    Sesija::obrisiSesiju();
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link href="css/npetanjek.css" rel="stylesheet" type="text/css">
        <link href="css/npetanjek_prilagodbe.css" rel="stylesheet" type="text/css">
        <title></title>
    </head>
    <body>
        <header>
            <div class="horizontalna_linija_brown"></div>
            <div class="zaglavlje">
                Poslovni prostor
            </div>
            <nav class="navigacija">
                <ul>
                    <li><a href="index.php">Početna</a></li>
                    <li><a href="prijavaRegistracija.php?mod=prijava">Prijava</a></li>
                    <li><a href="prijavaRegistracija.php?mod=registracija">Registracija</a></li>
                    <li><a href="lokacije.php">Lokacije</a></li>
                    <?php if (!isset($_SESSION["korisnik"])) { ?>
                        <li><a href="oglasi.php">Oglasi</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION["korisnik"]) && $_SESSION["tip"] == 3) { ?>
                        <div class="dropdown">
                            <li>Oglasi</li>
                            <div class="dropdown-content">
                                <a href="oglasi.php">Oglasi</a>
                                <a href="predajOglas.php">Predaj oglas</a>
                                <a href="predaniZahtjevi.php">Predani zahtjevi za oglase</a>
                                <a href="statistikaOglasa.php">Statistika oglasa</a>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION["korisnik"]) && $_SESSION["tip"] <= 2) { ?>
                        <li><a href="oglasi.php">Oglasi</a></li>    
                        <div class="dropdown">
                            <li>Upravljačka ploča</li>
                            <div class="dropdown-content">
                                <a href="upravljackaPloca.php?mod=zahtjeviZaBlokiranjeOglasa">Zahtjevi za blokiranje oglasa</a>
                                <a href="upravljackaPloca.php?mod=zahtjeviZaOglase">Zahtjevi za oglase</a>
                                <a href="upravljackaPloca.php?mod=kreirajDvoranu">Kreiraj dvoranu</a>
                                <a href="upravljackaPloca.php?mod=otkaziTermin">Otkaži termin</a>
                                <a href="upravljackaPloca.php?mod=dodjeljivanjeVrsteOglasa">Dodjeljivanje vrste oglasa</a>
                                <a href="upravljackaPloca.php?mod=kreiranjeVrsteOglasa">Kreiraj vrstu oglasa</a>
                                <?php if (isset($_SESSION["korisnik"]) && $_SESSION["tip"] == 1) { ?>
                                    <a href="upravljackaPloca.php?mod=kreirajLokaciju">Kreiraj lokaciju</a>
                                    <a href="upravljackaPloca.php?mod=moderatori">Kreiraj/dodijeli ulogu moderatora</a>
                                    <a href="upravljackaPloca.php?mod=statistikaKlikovaOglasa">Statistika klikova oglasa</a>
                                    <a href="upravljackaPloca.php?mod=statistikaPlacenihIznosa">Statistika plaćenih iznosa oglasa</a>
                                    <a href="upravljackaPloca.php?mod=topListaKorisnika">Top lista korisnika</a>
                                    <a href="upravljackaPloca.php?mod=zakljucaniKorisnici">Zaključani korisnički računi</a>
                                    <a href="upravljackaPloca.php?mod=dnevnikRada">Dnevnik rada</a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <li><a href="o_autoru.html">O autoru</a></li>
                    <li><a href="dokumentacija.html">Dokumentacija</a></li>
                    <?php if (isset($_SESSION["korisnik"])) { ?>
                        <li><a href="index.php?mod=odjava">Odjava</a></li>
                    <?php } ?>
                </ul>
            </nav>
            <div class="horizontalna_linija_maroon"></div>
        </header>
        <section>
            <?php
            if (isset($_GET["mod"]) && ($_GET["mod"]) == "blokiraj") {
                echo '<form novalidate method="POST">'
                . '<label for="razlog_blokiranja">Razlog blokiranja: </label>'
                . '<textarea name="razlog_blokiranja" rows="5" cols="50"></textarea>'
                . '<div class="clear"></div>'
                . '<input type="submit" class="gumb" id="prijavi_oglas" name="prijavi_oglas" value="Prijavi oglas">'
                . '</form>';
                if (isset($_POST["prijavi_oglas"])) {
                    $veza = new Baza();
                    $veza->spojiDB();
                    isset($_POST["razlog_blokiranja"])?$razlog_blokiranja = $_POST["razlog_blokiranja"]:$razlog_blokiranja="";
                    $sql = "INSERT INTO zahtjev_za_blokiranje_oglasa (id_korisnik, id_oglas, razlog) VALUES (" . $_SESSION["id"] . ", " . $_GET["id_oglas"] . ", '" . $razlog_blokiranja . "')";
                    $veza->updateDB($sql);
                    $sql_dnevnik = "SELECT * FROM oglas WHERE id_oglas=" . $_GET["id_oglas"];
                    $rs = $veza->selectDB($sql_dnevnik);
                    $rez = mysqli_fetch_array($rs);
                    $date = date('Y-m-d H:i:s');
                    $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Prijavljen oglas " . $rez[1] . ".', '" . $date . "')";
                    $veza->updateDB($sql_dnevnik);
                    $veza->zatvoriDB();
                }
            } else {
                $veza = new Baza();
                $veza->spojiDB();
                $sql = "SELECT * FROM oglas WHERE id_oglas=" . $_GET["mod"];
                $rs = $veza->selectDB($sql);
                echo '<form novalidate method="POST" action="oglasiWebMjesto.php?mod=blokiraj&id_oglas=' . $_GET["mod"] . '">';
                while ($row = mysqli_fetch_array($rs)) {
                    echo '<h3>' . $row["naslov"] . '</h3>';
                    echo '<p>' . $row["sadržaj"] . '</p>';
                    echo '<img src="./slike/' . $row["slika"] . '">';
                }
                if (isset($_SESSION["korisnik"])) {
                    echo '<input type="submit" class="gumb" id="prijavi_oglas" name="prijavi_oglas" value="Prijavi oglas">';
                }
                echo '</form>';
                $sql_klikovi = "UPDATE oglas SET broj_klikova= broj_klikova+1 WHERE id_oglas=" . $_GET["mod"];
                $veza->updateDB($sql_klikovi);
                $veza->zatvoriDB();
            }
            ?>
        </section>

    </body>
</html>
