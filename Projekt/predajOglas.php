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
            <?php
            $veza = new Baza();
            $veza->spojiDB();
            $sql = "SELECT * FROM `vrsta_oglasa`";
            $rs = $veza->selectDB($sql);
            echo '<form enctype="multipart/form-data" method="POST">'
            . '<table border="1">'
            . '<thead>'
            . '<tr>'
            . '<th>ID vrste</th><th>Trajanje prikazivanja oglasa (dana)</th><th>Brzina izmjene oglasa (s)</th><th>Cijena (kn)</th>'
            . '</tr>'
            . '</thead>';
            while ($red = mysqli_fetch_array($rs)) {
                echo '<tr><td>' . $red[0] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td><td>' . $red[4] . '</td></tr>';
            }
            echo '</table><br>'
            . '<div class="clear"></div><br>'
            . '<label class="label_lijevo" for="lista_vrsti">Vrsta oglasa: </label>'
            . '<input class="input_desno" name="lista_vrsti" list="vrste" id="lista_vrsti" placeholder="Odaberite vrstu oglasa"><br>'
            . '<datalist id="vrste">';
            $rs_vrsta = $veza->selectDB($sql);
            while ($red = mysqli_fetch_array($rs_vrsta)) {
                echo '<option value="' . $red["id_vrsta_oglasa"] . '">' . $red["id_vrsta_oglasa"] . '</option>';
            }
            echo '</datalist><br>'
            . '<label class="label_lijevo" for="naziv_oglasa">Naziv: </label>'
            . '<input type="text" class="input_desno" id="naziv_oglasa" name="naziv_oglasa"><br>'
            . '<label class="label_lijevo" for="opis_oglasa">Opis: </label>'
            . '<input type="text" class="input_desno" id="opis_oglasa" name="opis_oglasa"><br>'
            . '<label class="label_lijevo" for="url_oglasa">URL: </label>'
            . '<input type="text" class="input_desno" id="url_oglasa" name="url_oglasa"><br>'
            . '<label class="label_lijevo" for="aktivan_od">Aktivan od: </label>'
            . '<input type="date" class="input_desno" id="aktivan_od" name="aktivan_od"><br>'
            . '<label class="label_lijevo" for="aktivan_do">Aktivan do: </label>';
            $interval = $red["'trajanje_prikazivanja_oglasa (h)'"];
            $aktivan_do = date('Y-m-d', strtotime("+" . $interval . " days"));
            echo '<input type="date" class="input_desno" id="aktivan_do" name="aktivan_do" value=' . $aktivan_do . '><br>'
            . '<div class="clear"></div>'
            . '<input class="label_lijevo" type="file" name="slikaOglasa" id="slikaOglasa">'
            . '<input class="input_desno" type="text" id="nazivSlike" name="nazivSlike" placeholder="Naziv slike" readonly="readonly"><br>'
            . '<div class="clear"></div>'
            . '<input type="submit" class="gumb" id="kreiraj_zahtjev" name="kreiraj_zahtjev" value="Kreiraj zahtjev"><br>';

            if (isset($_POST["kreiraj_zahtjev"])) {
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

                $sql_upload = "INSERT INTO `zahtjev_za_kreiranjem_oglasa`(`id_korisnik`, `id_vrsta`, `naziv`, `opis`, `url`, `slika`, `aktivan_od`) VALUES (" . $_SESSION["id"] . ", " . $_POST["lista_vrsti"] . ", '" . $_POST["naziv_oglasa"] . "', '" . $_POST["opis_oglasa"] . "', '" . $_POST["url_oglasa"] . "', '" . $fileName . "', '" . $_POST["aktivan_od"] . "')";
                $success = $veza->updateDB($sql_upload);
                if ($success) {
                    echo "Vaš oglas je predan!";
                    $date = date('Y-m-d H:i:s');
                    $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Kreirao zahtjev za kreiranje oglasa " . $_POST["naziv_oglasa"] . ".', '" . $date . "')";
                    $veza->updateDB($sql_dnevnik);
                } else {
                    echo "Neuspjeh u slanju oglasa!";
                }
            }
            echo '</form>';
            ?>
        </section>
    </body>
</html>
