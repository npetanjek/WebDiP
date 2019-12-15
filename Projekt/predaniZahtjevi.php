<!DOCTYPE html>
<?php
require './baza.class.php';
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
            <div class="main">
                <div class="row">
                    <?php
                    $veza = new Baza();
                    $veza->spojiDB();
                    $sql = "SELECT * FROM `zahtjev_za_kreiranjem_oglasa` WHERE `id_korisnik`=" . $_SESSION["id"];
                    $rs = $veza->selectDB($sql);
                    while ($red = mysqli_fetch_array($rs)) {
                        $status = $red["status"];
                        switch ($status) {
                            case '1':
                                $status = 'Prihvaćen';
                                break;
                            case '0':
                                $status = 'Odbijen';
                                break;
                            default:
                                $status = 'Čeka se na odgovor moderatora';
                                break;
                        }
                        echo '<div class="column">
                                <a href=predaniZahtjevi.php?id_zahtjev=' . $red["id_zahtjev"] . '&status=' . $status . '><div class="content">
                                    <h3>' . $red["naziv"] . '</h3>
                                    <img src="./slike/' . $red["slika"] . '" alt="' . $red["slika"] . '" style="width:100%">
                                    <p>' . $red["opis"] . '</p>
                                        <p>url: ' . $red["url"] . '</p>
                                            <p>Aktivan od: ' . $red["aktivan_od"] . '</p>
                                                <p>Status: ' . $status . '</p>
                                </div></a>
                            </div>';
                    }
                    if (isset($_GET["status"]) && $_GET["status"] == 'Čeka') {
                        $sql2 = "SELECT * FROM `zahtjev_za_kreiranjem_oglasa` WHERE id_zahtjev=" . $_GET["id_zahtjev"];
                        $rs2 = $veza->selectDB($sql2);
                        $rez = mysqli_fetch_assoc($rs2);
                        echo '<form method="POST">'
                        . '<div class="clear"></div>'
                        . '<h3>Ažuriraj oglas</h3>'
                        . '<label class="label_lijevo" for="naslov">Naslov: </label>'
                        . '<input class="input_desno" type="text" id="naslov" name="naslov" value="' . $rez["naziv"] . '"><br>'
                        . '<label class="label_lijevo" for="sadrzaj">Sadržaj: </label>'
                        . '<input class="input_desno" type="text" id="sadrzaj" name="sadrzaj" value="' . $rez["opis"] . '"><br>'
                        . '<label class="label_lijevo" for="url">URL: </label>'
                        . '<input class="input_desno" type="text" id="url" name="url" value="' . $rez["url"] . '"><br>'
                        . '<label class="label_lijevo" for="aktivan_od">Aktivan od: </label>'
                        . '<input class="input_desno" type="date" id="aktivan_od" name="aktivan_od" value="' . $rez["aktivan_od"] . '"><br>'
                        . '<input class="label_lijevo" type="file" name="slikaOglasa" id="slikaOglasa"><br>'
                        . '<div class="clear"></div>'
                        . '<input type="submit" class="gumb" id="azuriraj_zahtjev" name="azuriraj_zahtjev" value="Ažuriraj zahtjev"><br>';
                        if (isset($_POST["azuriraj_zahtjev"])) {
                            $fileName = $_FILES['slikaOglasa']['name'];
                            $fileTmpName = $_FILES['slikaOglasa']['tmp_name'];
                            $fileSize = $_FILES['slikaOglasa']['size'];
                            $fileError = $_FILES['slikaOglasa']['error'];
                            $fileType = $_FILES['slikaOglasa']['type'];

                            $fileExt = explode('.', $fileName);
                            $fileActualExt = strtolower(end($fileExt));

                            $allowed = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');
                            if (in_array($fileActualExt, $allowed)) {
                                if ($fileError === 0) {
                                    if ($fileSize < 5242880) {
                                        $fileDestination = "/var/www/webdip.barka.foi.hr/2017_projekti/WebDiP2017x114/slike/" . $fileName;
                                        move_uploaded_file($fileTmpName, $fileDestination);
                                    } else {
                                        echo "Slika je veca od 5MB";
                                    }
                                } else {
                                    echo "Postoji greska u ucitavanju datoteke";
                                }
                            } else {
                                echo "Nedozvoljen format slike (jpg,jpeg,png)";
                            }
                            $sql_update = "UPDATE `zahtjev_za_kreiranjem_oglasa` SET `naziv`='" . $_POST["naslov"] . "',`opis`='" . $_POST["sadrzaj"] . "',`url`='" . $_POST["url"] . "' WHERE id_zahtjev=" . $_GET["id_zahtjev"];
                            $date = date('Y-m-d H:i:s');
                            $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Ažuriran zahtjev za kreiranjem oglasa " . $_POST["naslov"] . ".', '" . $date . "')";
                            $veza->updateDB($sql_dnevnik);
                            $veza->updateDB($sql_update);
                        }
                        echo '</form>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </body>
</html>
