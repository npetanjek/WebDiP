<!DOCTYPE html>
<?php
require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
require './sesija.class.php';
Sesija::kreirajSesiju();
/* if (!isset($_SESSION["korisnik"])) {
  header("Location: prijavaRegistracija.php?mod=prijava");
  } */
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
        <script type="text/javascript" src="js/npetanjek_jquery.js"></script>
        <title>Početna</title>
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
            <div id="slideshow-container12">
                <?php
                $oglas_podaci = array();
                $sql = "SELECT * FROM oglas o JOIN vrsta_za_poziciju vzp ON o.id_oglas=vzp.id_oglas WHERE vzp.id_pozicija=12 AND o.status IS NULL GROUP BY 1";
                $rs = $veza->selectDB($sql);
                $brojac = 0;
                while ($redak = mysqli_fetch_assoc($rs)) {
                    $oglas_podaci[] = $redak;
                }
//var_dump($oglas_podaci);
                foreach ($oglas_podaci as $value) {

                    echo '<div id="item-' . $brojac . '" class="slideshow-item12" >
                        <div>
                                <a href="oglasiWebMjesto.php?mod=' . $value["id_oglas"] . '"><div class="content">
                                    <h3>' . $value["naslov"] . '</h3>
                                    <img src="./slike/' . $value["slika"] . '" alt="' . $value["slika"] . '" style="width:100%">
                                    
                                    <p>' . $value["sadržaj"] . '</p>
                                    <p>Broj klikova: ' . $value["broj_klikova"] . '</p>   
                                </div></a>
                                
                            </div></div>';
                    $brojac++;
                }
                ?>
            </div>
            <div id="slideshow-container9">
                <?php
                $oglas_podaci9 = array();
                $sql9 = "SELECT * FROM oglas o JOIN vrsta_za_poziciju vzp ON o.id_oglas=vzp.id_oglas WHERE vzp.id_pozicija=9 AND o.status IS NULL GROUP BY 1";
                $rs9 = $veza->selectDB($sql9);
                $brojac9 = 0;
                while ($redak9 = mysqli_fetch_assoc($rs9)) {
                    $oglas_podaci9[] = $redak9;
                }
//var_dump($oglas_podaci);
                foreach ($oglas_podaci9 as $value) {

                    echo '<div id="item-' . $brojac9 . '" class="slideshow-item9" >
                        <div>
                                <a href="oglasiWebMjesto.php?mod=' . $value["id_oglas"] . '"><div class="content">
                                    <h3>' . $value["naslov"] . '</h3>
                                    <img src="./slike/' . $value["slika"] . '" alt="' . $value["slika"] . '" style="width:100%">
                                    
                                    <p>' . $value["sadržaj"] . '</p>
                                    <p>Broj klikova: ' . $value["broj_klikova"] . '</p>   
                                </div></a>
                                
                            </div></div>';
                    $brojac++;
                }
                $veza->zatvoriDB();
                ?>
            </div>
        </section>
    </body>
</html>
