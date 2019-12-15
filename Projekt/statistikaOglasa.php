<!DOCTYPE html>
<?php
require './sesija.class.php';
Sesija::kreirajSesiju();
if (!isset($_SESSION["korisnik"])) {
    header("Location: prijavaRegistracija.php?mod=prijava");
}
/* if($_SESSION["tip"]>1){
  exit("Nemate prava");
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="js2/npetanjek_jquery_upravljackaPloca.js"></script>
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
            require './baza.class.php';
            $veza = new Baza();
            $veza->spojiDB();
            $sql = "SELECT o.`naslov`, o.`sadržaj`, o.`broj_klikova`, zzko.id_vrsta FROM oglas o JOIN zahtjev_za_kreiranjem_oglasa zzko ON o.id_zahtjev=zzko.id_zahtjev WHERE zzko.id_korisnik=" . $_SESSION["id"];
            $rs = $veza->selectDB($sql);
            echo '<table border="1">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Naslov</th><th>Sadržaj</th><th>Vrsta</th><th>Broj klikova</th>';
            echo '</tr>';
            echo '</thead><tbody>';
            while ($red = mysqli_fetch_array($rs)) {
                echo '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td><td>' . $red[3] . '</td><td>' . $red[2] . '</td></tr>';
            }
            echo '</tbody></table><br>';
            ?>
            <canvas id="platnoStatistikaOglasa" width="600" height="400">
            </canvas>
        </section>
    </body>
</html>
