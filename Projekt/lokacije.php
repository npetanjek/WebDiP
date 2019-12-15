<!DOCTYPE html>
<?php
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
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/npetanjek_jquery.js"></script>
        <script src="js/npetanjek.js" type="text/javascript"></script>
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
            <div id="changeText"></div>
            <?php
            require './baza.class.php';
            if (isset($_GET["mod"]) && ($_GET["mod"]) == "prijava") {

                $veza = new Baza();
                $veza->spojiDB();
                $sql = "SELECT d.naziv, d.kapacitet, k.naziv, l.naziv, t.datum_pocetka_koristenja AS rezervirana_od, DATE_ADD( t.datum_pocetka_koristenja, INTERVAL t.`trajanje_koristenja` 
                DAY ) AS rezervirana_do
                FROM lokacija l
                JOIN dvorana d ON l.id_lokacija = d.id_lokacija
                JOIN vrsta_koristenja k ON d.id_vrsta_koristenja = k.id_vrsta_koristenja
                JOIN termin t ON d.id_dvorana = t.id_dvorana WHERE d.id_lokacija=" . $_GET["lokacija"] . " AND d.naziv='" . $_GET["dvorana"] . "' AND DATE_ADD( t.datum_pocetka_koristenja, INTERVAL  t.`trajanje_koristenja` DAY ) < CURRENT_TIMESTAMP GROUP BY (d.naziv)";
                $rs = $veza->selectDB($sql);
                echo '<table border="1">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Dvorana</th><th>Kapacitet</th><th>Vrsta korištenja</th><th>Lokacija</th>';
                echo '</tr>';
                echo '</thead>';
                while ($red = mysqli_fetch_array($rs)) {
                    echo '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td>';
                }
                echo '</table><br>';
                echo '<div class="clear"></div>';
                echo '<form novalidate id="form_prijava" name="form_prijava" method="post" action="' . 'lokacije.php?lokacija=' . $_GET["lokacija"] . '&dvorana=' . $_GET["dvorana"] . '' . '">;';
                echo '<label class="label_lijevo" for="mailprijava">E-mail: </label>
                    <input type="text" class="input_desno" id="mailprijava" name="mailprijava">
                    <br>
                    <label class="label_lijevo" for="datumpocetkakoristenja">Datum početka korištenja: </label>
                    <input type="date" class="input_desno" id="datumpocetkakoristenja" name="datumpocetkakoristenja" placeholder="YYYY-mm-dd"
                    <br>';
                echo '<div class="clear"></div>';
                echo '<input type="submit" class="gumb" id="prijava" name="prijava" value="Prijava">';
                echo '</form>';
                $veza->zatvoriDB();
            } else if (isset($_GET["mod"]) && ($_GET["mod"]) !== "") {
                $veza = new Baza();
                $veza->spojiDB();
                $sql = "SELECT d.naziv, d.kapacitet, k.naziv FROM dvorana d JOIN vrsta_koristenja k ON d.id_vrsta_koristenja=k.id_vrsta_koristenja WHERE d.id_lokacija=" . $_GET["mod"];
                $rs = $veza->selectDB($sql);
                echo '<table border="1">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Dvorana</th><th>Kapacitet</th><th>Vrsta korištenja</th>';
                echo '</tr>';
                echo '</thead>';
                while ($red = mysqli_fetch_array($rs)) {
                    echo '<tr><td><a href=lokacije.php?mod=prijava&lokacija=' . $_GET["mod"] . '&dvorana=' . $red[0] . '>' . $red[0] . '</a></td><td>' . $red[1] . '</td><td>' . $red[2] . '</td></tr>';
                }
                echo '</table>';
            } else {
                $veza = new Baza();
                $veza->spojiDB();
                $sql = "SELECT * FROM lokacija";
                $rs = $veza->selectDB($sql);
                echo '<ol style="font-family: arial">';
                while ($red = mysqli_fetch_array($rs)) {

                    echo '<li class="popis_lokacija"><a href="lokacije.php?mod=' . $red["id_lokacija"] . '">' . $red["naziv"] . '</a></li>';
                }
                echo '</ol>';
                $veza->zatvoriDB();
            }
            ?>
            <?php
            if (isset($_POST["prijava"])) {
                $veza = new Baza();
                $veza->spojiDB();
                $datumpocetkakoristenja = $_POST["datumpocetkakoristenja"];
                $datumformatiran = date("Y-m-d", strtotime($datumpocetkakoristenja));
                $nazivDvorane = $_GET["dvorana"];
                $sql = "UPDATE termin SET datum_pocetka_koristenja='" . $datumformatiran . "' WHERE id_dvorana=(SELECT id_dvorana FROM dvorana WHERE naziv='" . $nazivDvorane . "')";
                $veza->updateDB($sql);
                $veza->zatvoriDB();
                $randomcode = randomCode();
                $mail = $_POST["mailprijava"];
                $mail_to = $mail;
                $subject = "Rezervacija dvorane";
                $message = "Poštovani, Vaša dvorana " . $_GET["dvorana"] . " na lokaciji " . $_GET["lokacija"] . " je rezervirana./nVaš kod za povratnu informaciju je: $randomcode";
                $headers = "From:Admin@barka.foi.hr" . "\r\n";
                mail($mail_to, $subject, $message, $headers);
                $date = date('Y-m-d H:i:s');
                $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Rezervirao dvoranu " . $_GET["dvorana"] . " na lokaciji " . $_GET["lokacija"] . ".', '" . $date . "')";
                $veza->updateDB($sql_dnevnik);
            }

            function randomCode() {
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $pass = array();
                $alphaLength = strlen($alphabet) - 1;
                for ($i = 0; $i < 16; $i++) {
                    $n = rand(0, $alphaLength);
                    $pass[] = $alphabet[$n];
                }
                return implode($pass);
            }
            ?>
            <div id="slideshow-container5">
                <?php
                $veza = new Baza();
                $veza->spojiDB();
                $oglas_podaci = array();
                $sql = "SELECT * FROM oglas o JOIN vrsta_za_poziciju vzp ON o.id_oglas=vzp.id_oglas WHERE vzp.id_pozicija=5 AND o.status IS NULL GROUP BY 1";
                $rs = $veza->selectDB($sql);
                $brojac = 0;
                while ($redak = mysqli_fetch_assoc($rs)) {
                    $oglas_podaci[] = $redak;
                }
                //var_dump($oglas_podaci);
                foreach ($oglas_podaci as $value) {
                    echo '<div id="item-' . $brojac . '" class="slideshow-item5" >
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
            <div id="slideshow-container10">
                <?php
                $veza = new Baza();
                $veza->spojiDB();
                $oglas_podaci = array();
                $sql = "SELECT * FROM oglas o JOIN vrsta_za_poziciju vzp ON o.id_oglas=vzp.id_oglas WHERE vzp.id_pozicija=10 AND o.status IS NULL GROUP BY 1";
                $rs = $veza->selectDB($sql);
                $brojac = 0;
                while ($redak = mysqli_fetch_assoc($rs)) {
                    $oglas_podaci[] = $redak;
                }
                //var_dump($oglas_podaci);
                foreach ($oglas_podaci as $value) {
                    echo '<div id="item-' . $brojac . '" class="slideshow-item10" >
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
