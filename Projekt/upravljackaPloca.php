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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="js2/npetanjek_jquery_upravljackaPloca.js"></script>
        <script type="text/javascript" src="js2/npetanjek_jquery_upravljackaPloca_stranicenje.js"></script>
        <title>Upravljačka ploča</title>
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

            switch ($_GET["mod"]) {

                case 'zahtjeviZaBlokiranjeOglasa':

                    echo '<form method="POST">';
                    echo '<div class="table-responsive" id="pagination_data">'
                    . '</div><br>'
                    . '<div class="clear"></div>';
                    echo '<input type="submit" class="gumb" id="odobri_zahtjev" name="odobri_zahtjev" value="Odobri zahtjev"><br>'
                    . '<input type="submit" class="gumb" id="odbij_zahtjev" name="odbij_zahtjev" value="Odbij zahtjev"><br>'
                    . '</form>';
                    if (isset($_POST["odobri_zahtjev"])) {
                        $sql = "UPDATE oglas SET status=0 WHERE id_oglas=" . $_GET["id_oglas"];
                        $veza->updateDB($sql);
                        $sql_mail = "SELECT o.id_oglas, o.naslov, zzbo.razlog, k.korisnicko_ime, k.email FROM zahtjev_za_blokiranje_oglasa zzbo JOIN oglas o ON zzbo.id_oglas = o.id_oglas JOIN zahtjev_za_kreiranjem_oglasa zzko ON o.id_zahtjev = zzko.id_zahtjev JOIN korisnik k ON zzko.id_korisnik = k.id_korisnik WHERE zzbo.id_zahtjev=" . $_GET["id_zahtjev"];
                        $rs = $veza->selectDB($sql_mail);
                        $rez = mysqli_fetch_array($rs);
                        $korime = $rez[3];
                        $naziv_oglasa = $rez[1];
                        $razlog_blokiranja = $rez[2];
                        $mail_to = $rez[4];
                        $subject = "Blokiran oglas";
                        $message = "Poštovani " . $korime . ", Vaš oglas " . $naziv_oglasa . " je blokiran te se više neće prikazivati na našoj stranici. Razlog blokiranja je: " . $razlog_blokiranja;
                        $headers = "From:Admin@barka.foi.hr" . "\r\n";
                        mail($mail_to, $subject, $message, $headers);
                        $date = date('Y-m-d H:i:s');
                        $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Odobren zahtjev za blokiranje oglasa " . $naziv_oglasa . ".', '" . $date . "')";
                        $veza->updateDB($sql_dnevnik);
                    } else if (isset($_POST["odbij_zahtjev"])) {
                        $sql = "DELETE FROM zahtjev_za_blokiranje_oglasa WHERE id_korisnik=" . $_GET["id_korisnik"] . " AND id_oglas=" . $_GET["id_oglas"];
                        $veza->updateDB($sql);
                    }
                    break;

                case 'zahtjeviZaOglase':
                    ?>
                    <!-- MAIN (Center website) -->
                    <div class="main">
                        <div class="row">
                            <?php
                            $sql = "SELECT zzko.`id_zahtjev`, zzko.`id_korisnik`, zzko.`id_vrsta`, zzko.`naziv`, zzko.`opis`, zzko.`url`, zzko.`slika`, zzko.`aktivan_od`, zzko.`status` FROM `zahtjev_za_kreiranjem_oglasa` zzko JOIN vrste_na_pozicijama vnp ON zzko.`id_vrsta`=vnp.id_vrsta JOIN vrsta_oglasa vo ON vnp.id_vrsta=vo.id_vrsta_oglasa WHERE vo.id_moderator=" . $_SESSION["id"] . " AND zzko.status IS NULL";
                            $rs = $veza->selectDB($sql);
                            echo '<form method="POST">';
                            /* echo '<table border="1">';
                              echo '<thead>';
                              echo '<tr>';
                              echo '<th>Korisnik</th><th>Naslov oglasa</th><th>Sadržaj oglasa</th><th>url</th><th>Aktivan od</th>';
                              echo '</tr>';
                              echo '</thead>'; */
                            while ($red = mysqli_fetch_array($rs)) {
                                echo '<div class="column">
                                <a href=upravljackaPloca.php?mod=zahtjeviZaOglase&id_zahtjev=' . $red[0] . '&id_vrsta=' . $red[2] . '><div class="content">
                                    <h3>' . $red[3] . '</h3>
                                    <img src="./slike/' . $red[6] . '" alt="' . $red[6] . '" style="width:100%">
                                    
                                    <p>' . $red[4] . '</p>
                                        <p>url: ' . $red[5] . '</p>
                                            <p>Aktivan od: ' . $red[7] . '</p>
                                </div></a>
                            </div>';
                                //echo '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td><td>' . $red[4] . '</td>';
                            }
                            //echo '</table><br>';

                            echo '<div class="clear"></div>'
                            . '<input type="submit" class="gumb" id="odobri_zahtjev" name="odobri_zahtjev" value="Odobri zahtjev"><br>'
                            . '<input type="submit" class="gumb" id="odbij_zahtjev" name="odbij_zahtjev" value="Odbij zahtjev"><br>'
                            . '</form>';
                            if (isset($_POST["odobri_zahtjev"])) {
                                $sqlvalues = "SELECT * FROM zahtjev_za_kreiranjem_oglasa WHERE id_zahtjev=" . $_GET["id_zahtjev"];
                                $rsvalues = $veza->selectDB($sqlvalues);
                                $values = mysqli_fetch_assoc($rsvalues);
                                $vrsta_oglasa = $values["id_vrsta"];
                                $korisnik = $values["id_korisnik"];
                                $naslov = $values["naziv"];
                                $sadrzaj = $values["opis"];
                                $slika = $values["slika"];
                                $sql_current_id = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'WebDiP2017x114' AND TABLE_NAME = 'oglas'";
                                $rs_current_id = $veza->selectDB($sql_current_id);
                                $current_id_value = mysqli_fetch_assoc($rs_current_id);
                                $get_current_id = $current_id_value["AUTO_INCREMENT"];
                                $sql_pozicija = "SELECT id_pozicija FROM vrste_na_pozicijama WHERE id_vrsta =" . $vrsta_oglasa;
                                $rs_pozicija = $veza->selectDB($sql_pozicija);
                                $current_pozicija = mysqli_fetch_assoc($rs_pozicija);
                                $get_pozicija = $current_pozicija["id_pozicija"];
                                $insert_oglas = "INSERT INTO oglas (naslov, sadržaj, slika, id_zahtjev) VALUES ('$naslov', '$sadrzaj', '$slika', " . $_GET["id_zahtjev"] . ")";
                                $insert_na_poziciju = "INSERT INTO `vrsta_za_poziciju`(`id_oglas`, `id_vrsta`, `id_pozicija`) VALUES ($get_current_id,$vrsta_oglasa,$get_pozicija)";
                                $update_status = "UPDATE `zahtjev_za_kreiranjem_oglasa` SET `status`=1 WHERE `id_zahtjev`=" . $_GET["id_zahtjev"];
                                $veza->updateDB($insert_oglas);
                                $veza->updateDB($insert_na_poziciju);
                                $veza->updateDB($update_status);
                                $date = date('Y-m-d H:i:s');
                                $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Odobren zahtjev za kreiranje oglasa " . $naslov . ".', '" . $date . "')";
                                $veza->updateDB($sql_dnevnik);
                            }
                            if (isset($_POST["odbij_zahtjev"])) {
                                $sql = "UPDATE `zahtjev_za_kreiranjem_oglasa` SET `status`=0 WHERE `id_zahtjev`=" . $_GET["id_zahtjev"];
                                $veza->updateDB($sql);
                                $date = date('Y-m-d H:i:s');
                                $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Odbijen zahtjev za kreiranje oglassa " . $naslov . ".', '" . $date . "')";
                                $veza->updateDB($sql_dnevnik);
                            }
                            break;
                            ?>
                        </div>
                    </div>
                <?php
                case 'kreirajDvoranu':
                    $sql_vrsta_koristenja = "SELECT * FROM vrsta_koristenja";
                    $rs_vrsta_koristenja = $veza->selectDB($sql_vrsta_koristenja);

                    echo '<form novalidate id="form_unos_dvorane" name="form_unos_dvorane" method="post" action="upravljackaPloca.php?mod=kreirajDvoranu">'
                    . '<label class="label_lijevo" for="lista_vrsta_koristenja">Vrsta korištenja: </label>'
                    . '<input class="input_desno" name="vrsta_koristenja" list="vrste_koristenja" id="lista_vrsta_koristenja" placeholder="Odaberite vrstu korištenja"><br>'
                    . '<datalist id="vrste_koristenja">';
                    while ($red = mysqli_fetch_array($rs_vrsta_koristenja)) {
                        echo '<option value="' . $red["id_vrsta_koristenja"] . '">' . $red["naziv"] . '</option>';
                    }
                    echo '</datalist><br>'
                    . '<label class="label_lijevo" for="lista_lokacija">Lokacija: </label>'
                    . '<input class="input_desno" name="lokacija" list="lokacije" id="lista_lokacija" placeholder="Odaberite lokaciju"><br>'
                    . '<datalist id="lokacije">';
                    $sql_lokacije = "SELECT l.id_lokacija, l.naziv FROM lokacija l JOIN moderira m ON l.id_lokacija=m.id_lokacija WHERE m.id_moderator=" . $_SESSION["id"];
                    $rs_lokacije = $veza->selectDB($sql_lokacije);
                    while ($red = mysqli_fetch_array($rs_lokacije)) {
                        echo '<option value="' . $red[0] . '">' . $red[1] . '</option>';
                    }
                    echo '</datalist><br>'
                    . '<label class="label_lijevo" for="naziv_dvorane">Naziv dvorane: </label>'
                    . '<input class="input_desno" type="text" id="naziv_dvorane" name="naziv_dvorane"><br>'
                    . '<label class="label_lijevo" for="kapacitet_dvorane">Kapacitet dvorane: </label>'
                    . '<input class="input_desno" type="text" id="kapacitet_dvorane" name="kapacitet_dvorane"><br>'
                    . '<div class="clear"></div>'
                    . '<input type="submit" class="gumb" id="kreiraj_dvoranu" name="kreiraj_dvoranu" value="Kreiraj dvoranu"><br>';
                    if (isset($_POST["kreiraj_dvoranu"])) {
                        $sql = "INSERT INTO dvorana (id_moderator, id_vrsta_koristenja, id_lokacija, naziv, kapacitet) VALUES(" . $_SESSION["id"] . ", " . $_POST["vrsta_koristenja"] . ", " . $_POST["lokacija"] . ", '" . $_POST["naziv_dvorane"] . "', " . $_POST["kapacitet_dvorane"] . ")";
                        $veza->updateDB($sql);
                        echo 'Dvorana je kreirana!';
                        $date = date('Y-m-d H:i:s');
                        $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Kreirana dvorana " . $_POST["naziv_dvorane"] . ".', '" . $date . "')";
                        $veza->updateDB($sql_dnevnik);
                    }
                    echo '</form>';
                    break;
                case 'otkaziTermin':
                    $sql = "SELECT t.id_termin, d.naziv, t.rok_prijave, t.datum_pocetka_koristenja, DATE_ADD( t.datum_pocetka_koristenja, INTERVAL t.`trajanje_koristenja` DAY ) 
            FROM termin t
            JOIN dvorana d ON t.id_dvorana = d.id_dvorana
            WHERE t.id_moderator=" . $_SESSION["id"];
                    $rs = $veza->selectDB($sql);
                    echo '<form method="POST">'
                    . '<table id="table" border="1">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID termina</th><th>Dvorana</th><th>Rok prijave</th><th>Datum početka korištenja</th><th>Datum završetka korištenja</th>';
                    echo '</tr>';
                    echo '</thead>';
                    while ($red = mysqli_fetch_array($rs)) {
                        echo '<tr><td><a href=upravljackaPloca.php?mod=otkaziTermin&id_termin=' . $red[0] . '>' . $red[0] . '</a></td><td>' . $red[1] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td><td>' . $red[4] . '</td></tr>';
                    }
                    echo '</table><br>'
                    . ''
                    . '<label class="label_lijevo" for="novi_termin">Novi termin: </label>'
                    . '<input class="input_desno" type="date" id="novi_termin" name="novi_termin"><br>'
                    . '<div class="clear"></div>'
                    . '<input type="submit" class="gumb" id="otkazi_termin" name="otkazi_termin" value="Otkaži termin"><br>'
                    . '</form>';

                    if (isset($_POST["otkazi_termin"])) {
                        $novi_termin = $_POST["novi_termin"];
                        $sqlmail = "SELECT * FROM termin WHERE id_termin=" . $_GET["id_termin"];
                        $rs = $veza->selectDB($sqlmail);
                        $rezultat = mysqli_fetch_assoc($rs);
                        $mail_to = $rezultat["email_rezervacija"];
                        $datum_koristenja = $rezultat["datum_pocetka_koristenja"];
                        $datumformatiran = date("Y-m-d", strtotime($novi_termin));
                        $sql = "UPDATE termin SET datum_pocetka_koristenja='" . $datumformatiran . "' WHERE id_termin=" . $_GET["id_termin"];
                        $veza->updateDB($sql);

                        $subject = "Rezervacija dvorane";
                        $message = "Poštovani, Vaš termin " . $datum_koristenja . " je otkazan. Novi termin je: " . $datumformatiran;
                        $headers = "From:Admin@barka.foi.hr" . "\r\n";
                        mail($mail_to, $subject, $message, $headers);
                        $date = date('Y-m-d H:i:s');
                        $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Otkazan termin " . $datum_koristenja . ", postavljen novi termin " . $datumformatiran . ".', '" . $date . "')";
                        $veza->updateDB($sql_dnevnik);
                    }
                    break;
                case 'dodjeljivanjeVrsteOglasa':
                    $sql = "SELECT po.id_pozicija, po.dimenzija_oglasa, s.url, l.naziv FROM pozicija_oglasa po JOIN lokacija l ON po.id_lokacija=l.id_lokacija JOIN stranica s ON po.id_stranica=s.id_stranica WHERE po.id_moderator=" . $_SESSION["id"];
                    $rs = $veza->selectDB($sql);
                    echo '<div id="tablicaVrstaOglasa"></div>'
                    . '<div id="tablicaVrsta"></div><br>'
                    . '<div id="listaVrsta"></div><br>'
                    . '<div class="clear"></div>'
                    . '<input type="submit" class="gumb" id="dodijeli_vrstu" name="dodijeli_vrstu" value="Dodijeli vrstu"><br>';
                    break;
                case 'kreirajLokaciju':
                    $sql = "SELECT l.id_lokacija, l.naziv, l.adresa, l.broj_telefona, l.`e-mail`, k.korisnicko_ime FROM lokacija l JOIN moderira m ON l.id_lokacija=m.id_lokacija JOIN korisnik k ON m.id_moderator=k.id_korisnik";
                    $rs = $veza->selectDB($sql);
                    echo '<form method="POST">'
                    . '<table id="table" border="1">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID lokacije</th><th>Naziv lokacije</th><th>Adresa</th><th>Broj telefona</th><th>E-mail</th><th>Moderator</th>';
                    echo '</tr>';
                    echo '</thead>';
                    while ($red = mysqli_fetch_array($rs)) {
                        echo '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td><td>' . $red[4] . '</td><td>' . $red[5] . '</td></tr>';
                    }
                    echo '</table><br>'
                    . '<label class="label_lijevo" for="id_lokacija">ID lokacije: </label>'
                    . '<input class="input_desno" type="text" id="id_lokacija" name="id_lokacija"><br>'
                    . '<label class="label_lijevo" for="naziv_lokacije">Naziv lokacije: </label>'
                    . '<input class="input_desno" type="text" id="naziv_lokacije" name="naziv_lokacije"><br>'
                    . '<label class="label_lijevo" for="adresa">Adresa: </label>'
                    . '<input class="input_desno" type="text" id="adresa" name="adresa"><br>'
                    . '<label class="label_lijevo" for="broj_telefona">Broj telefona: </label>'
                    . '<input class="input_desno" type="text" id="broj_telefona" name="broj_telefona"><br>'
                    . '<label class="label_lijevo" for="email">e-mail: </label>'
                    . '<input class="input_desno" type="text" id="email" name="email"><br>'
                    . '<label class="label_lijevo" for="moderator">Moderator: </label>'
                    . '<input class="input_desno" name="moderator" list="lista_moderatora" id="moderator" placeholder="Odaberite moderatora"><br>'
                    . '<datalist name="NEKI" id="lista_moderatora">';
                    $sql_moderatori = "SELECT * FROM korisnik WHERE id_uloga=2";
                    $rs_moderatori = $veza->selectDB($sql_moderatori);
                    while ($red = mysqli_fetch_array($rs_moderatori)) {
                        echo '<option value="' . $red["id_korisnik"] . '">' . $red["korisnicko_ime"] . '</option>';
                    }
                    echo '</datalist><br>'
                    . '<div class="clear"></div>'
                    . '<input type="submit" class="gumb" id="kreiraj_lokaciju" name="kreiraj_lokaciju" value="Kreiraj lokaciju">'
                    . '<input type="submit" class="gumb" id="uredi_lokaciju" name="uredi_lokaciju" value="Uredi lokaciju">'
                    . '<input type="submit" class="gumb" id="brisi_lokaciju" name="brisi_lokaciju" value="Briši lokaciju"><br>'
                    . '</form>';
                    if (isset($_POST["kreiraj_lokaciju"])) {
                        $sql = "INSERT INTO `lokacija`(`id_lokacija`, `naziv`, `adresa`, `broj_telefona`, `e-mail`) VALUES (" . $_POST["id_lokacija"] . ",'" . $_POST["naziv_lokacije"] . "','" . $_POST["adresa"] . "','" . $_POST["broj_telefona"] . "','" . $_POST["email"] . "')";
                        $veza->updateDB($sql);
                        $sql2 = 'INSERT INTO `moderira`(`id_moderator`, `id_lokacija`) VALUES (' . $_POST["moderator"] . ',' . $_POST["id_lokacija"] . ')';
                        $veza->updateDB($sql2);
                        var_dump($_POST["moderator"] . ' ' . $_POST["id_lokacija"]);
                        $date = date('Y-m-d H:i:s');
                        $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Kreirana lokacija " . $_POST["naziv_lokacije"] . ".', '" . $date . "')";
                        $veza->updateDB($sql_dnevnik);
                    }
                    if (isset($_POST["uredi_lokaciju"])) {
                        $sql = "UPDATE lokacija SET `naziv`='" . $_POST["naziv_lokacije"] . "', `adresa`='" . $_POST["adresa"] . "', `broj_telefona`='" . $_POST["broj_telefona"] . "', `e-mail`='" . $_POST["email"] . "' WHERE id_lokacija=" . $_POST["id_lokacija"];
                        $veza->updateDB($sql);
                        $date = date('Y-m-d H:i:s');
                        $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Uređena lokacija " . $_POST["naziv_lokacije"] . ".', '" . $date . "')";
                        $veza->updateDB($sql_dnevnik);
                    }
                    if (isset($_POST["brisi_lokaciju"])) {
                        $sql = "DELETE FROM lokacija WHERE id_lokacija=" . $_POST["id_lokacija"];
                        $veza->updateDB($sql);
                        $date = date('Y-m-d H:i:s');
                        $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Obrisana lokacija " . $_POST["id_lokacija"] . ".', '" . $date . "')";
                        $veza->updateDB($sql_dnevnik);
                    }
                    break;
                case 'topListaKorisnika':
                    isset($_POST["broj_redaka"]) ? $limit = $_POST["broj_redaka"] : $limit = 5;
                    $sql = "SELECT k.korisnicko_ime, vo.id_vrsta_oglasa, SUM(vo.cijena) FROM korisnik k JOIN zahtjev_za_kreiranjem_oglasa zzko ON k.id_korisnik=zzko.id_korisnik JOIN vrsta_oglasa vo ON zzko.id_vrsta=vo.id_vrsta_oglasa WHERE zzko.status=1 GROUP BY 1 ORDER BY 3 DESC LIMIT " . $limit;
                    $rs = $veza->selectDB($sql);
                    echo '<form method="POST">'
                    . '<table id="table" border="1">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Korisnik</th><th>Iznos plaćenih oglasa</th>';
                    echo '</tr>';
                    echo '</thead><tbody>';
                    while ($red = mysqli_fetch_array($rs)) {
                        echo '<tr><td>' . $red[0] . '</td><td>' . $red[2] . '</td></tr>';
                    }
                    echo '</tbody></table><br>';
                    echo '<label class="label_lijevo" for="broj_redaka">Broj prikazanih korisnika: </label>'
                    . '<input class="input_desno" type="text" id="broj_redaka" name="broj_redaka"><br>'
                    . '<div class="clear"></div>'
                    . '<input type="submit" class="gumb" id="filter" name="filter" value="Filtriraj">';
                    if (isset($_POST["filter"])) {
                        $limit = $_POST["broj_redaka"];
                    }
                    echo '</form><br>';
                    ?>
                    <canvas id="platnoTopListaKorisnika" width="600" height="400">
                    </canvas>
                    <?php
                    break;
                case 'kreiranjeVrsteOglasa':

                    break;
                case 'statistikaKlikovaOglasa':
                    isset($_POST["filtriraj_korisnik"]) ? $korisnicko_ime = $_POST["filtriraj_korisnik"] : $korisnicko_ime = "";
                    isset($_POST["aktivan_od"]) ? $aktivan_od = $_POST["aktivan_od"] : $aktivan_od = "";
                    isset($_POST["aktivan_do"]) ? $aktivan_do = $_POST["aktivan_do"] : $aktivan_do = "";
                    echo '<form method="POST">'
                    . '<table id="table" border="1">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Korisnik</th><th>Naslov oglasa</th><th>Sadržaj oglasa</th><th>Broj klikova</th>';
                    echo '</tr>';
                    echo '</thead><tbody>';

                    if (isset($_POST["filter"])) {

                        $sql = "SELECT k.korisnicko_ime, o.naslov, o.sadržaj, o.broj_klikova FROM oglas o JOIN zahtjev_za_kreiranjem_oglasa zzko ON o.id_zahtjev=zzko.id_zahtjev JOIN korisnik k ON zzko.id_korisnik=k.id_korisnik WHERE k.korisnicko_ime='" . $korisnicko_ime . "' AND zzko.aktivan_od > '" . $aktivan_od . "' AND zzko.aktivan_od < '" . $aktivan_do . "'";
                        $rs = $veza->selectDB($sql);
                        while ($red = mysqli_fetch_array($rs)) {
                            if ($red) {
                                echo '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td></tr>';
                            }
                        }
                    } else {
                        $broj_klikova = array();
                        $sql = "SELECT k.korisnicko_ime, o.naslov, o.sadržaj, o.broj_klikova FROM oglas o JOIN zahtjev_za_kreiranjem_oglasa zzko ON o.id_zahtjev=zzko.id_zahtjev JOIN korisnik k ON zzko.id_korisnik=k.id_korisnik";
                        $rs = $veza->selectDB($sql);
                        while ($red = mysqli_fetch_array($rs)) {
                            echo '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td></tr>';
                            $broj_klikova[] = $red[3];
                        }
                    }
                    echo '</tbody></table><br>';
                    $sql_lista_korisnika = "SELECT k.korisnicko_ime FROM oglas o JOIN zahtjev_za_kreiranjem_oglasa zzko ON o.id_zahtjev=zzko.id_zahtjev JOIN korisnik k ON zzko.id_korisnik=k.id_korisnik GROUP BY 1";
                    $rs_lista_korisnika = $veza->selectDB($sql_lista_korisnika);
                    echo '<label class="label_lijevo" for="filtriraj_korisnik">Filtriraj po korisniku: </label>'
                    . '<input class="input_desno" name="filtriraj_korisnik" list="lista_korisnika" id="filtriraj_korisnik" placeholder="Odaberite korisnika"><br>'
                    . '<datalist name="lista_korisnika" id="lista_korisnika">';
                    while ($red = mysqli_fetch_array($rs_lista_korisnika)) {
                        echo '<option value="' . $red[0] . '">' . $red[0] . '</option>';
                    }
                    echo '</datalist>'
                    . '<label class="label_lijevo" for="aktivan_od">Početni datum: </label>'
                    . '<input class="input_desno" name="aktivan_od" id="aktivan_od" type="date">'
                    . '<label class="label_lijevo" for="aktivan_do">Završni datum: </label>'
                    . '<input class="input_desno" name="aktivan_do" id="aktivan_do" type="date"><br>';
                    echo '<div class="clear"></div>'
                    . '<input type="submit" class="gumb" id="filter" name="filter" value="Filtriraj">';
                    echo '</form><br>';
                    ?>
                    <canvas id="platno" width="600" height="400">
                    </canvas>
                    <?php
                    break;
                case 'statistikaPlacenihIznosa':
                    $sql = "SELECT id_vrsta, COUNT(id_vrsta) * (SELECT cijena FROM vrsta_oglasa WHERE id_vrsta_oglasa = id_vrsta) FROM zahtjev_za_kreiranjem_oglasa WHERE STATUS =1 GROUP BY 1  ORDER BY 1 DESC";
                    $rs = $veza->selectDB($sql);
                    echo '<form method="POST">'
                    . '<table id="table" border="1">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Vrsta oglasa</th><th>Ukupni plaćeni iznos za vrstu</th>';
                    echo '</tr>';
                    echo '</thead><tbody>';
                    while ($red = mysqli_fetch_array($rs)) {
                        echo '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td></tr>';
                    }
                    echo '</tbody></table><br>'
                    . '</form>';
                    ?>
                    <canvas id="platnoPlaceniIznosi" width="600" height="400">
                    </canvas>
                    <?php
                    break;
                case 'zakljucaniKorisnici':
                    $sql = "SELECT * FROM korisnik WHERE aktivan=0";
                    $rs = $veza->selectDB($sql);
                    echo '<form method="POST">'
                    . '<table id="table" border="1">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID Korisnik</th><th>Korisnik</th><th>Email</th>';
                    echo '</tr>';
                    echo '</thead><tbody>';
                    while ($red = mysqli_fetch_array($rs)) {
                        echo '<tr><td><a href=upravljackaPloca.php?mod=zakljucaniKorisnici&id_korisnik=' . $red[0] . '>' . $red[0] . '</a></td><td>' . $red[4] . '</td><td>' . $red[8] . '</td></tr>';
                    }
                    echo '</tbody></table><br>';
                    echo '<div class="clear"></div>'
                    . '<input type="submit" class="gumb" id="otkljucaj" name="otkljucaj" value="Otključaj">';
                    if (isset($_POST["otkljucaj"])) {
                        $sql_select_dnevnik = "SELECT * FROM korisnik WHERE id_korisnik=" . $_GET["id_korisnik"];
                        $rs = $veza->selectDB($sql_select_dnevnik);
                        $rez = mysqli_fetch_array($rs);
                        $sql = "UPDATE korisnik SET broj_neuspj_prijava = 0, aktivan = 1 WHERE id_korisnik=" . $_GET["id_korisnik"];
                        $veza->updateDB($sql);
                        $date = date('Y-m-d H:i:s');
                        $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Otključan korisnički račun " . $rez[4] . ".', '" . $date . "')";
                        $veza->updateDB($sql_dnevnik);
                    }
                    echo '</form>';
                    break;
                case 'dnevnikRada':
                    $sql = "SELECT * FROM dnevnik_rada";
                    $rs = $veza->selectDB($sql);
                    echo '<table id="table" border="1">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Korisnik</th><th>Aktivnost</th><th>Vrijeme</th>';
                    echo '</tr>';
                    echo '</thead><tbody>';
                    while ($red = mysqli_fetch_array($rs)) {
                        echo '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td><td>' . $red[2] . '</td></tr>';
                    }
                    echo '</tbody></table><br>';

                    break;
                case 'moderatori':
                    $sql = "SELECT k.id_korisnik, k.korisnicko_ime, u.naziv, k.ime, k.prezime, k.email FROM korisnik k JOIN uloga u ON k.id_uloga=u.id_uloga WHERE aktivan=1";
                    $rs = $veza->selectDB($sql);
                    echo '<form method="POST">';
                    echo '<div style="overflow-x: auto;">';
                    echo '<table id="table" border="1">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Korisnik</th><th>Uloga</th><th>Ime</th><th>Prezime</th><th>e-mail</th>';
                    echo '</tr>';
                    echo '</thead><tbody>';
                    while ($red = mysqli_fetch_array($rs)) {
                        echo '<tr><td><a href=upravljackaPloca.php?mod=moderatori&korisnik=' . $red[1] . '>' . $red[1] . '</a></td><td>' . $red[2] . '</td><td>' . $red[3] . '</td><td>' . $red[4] . '</td><td>' . $red[5] . '</td></tr>';
                    }
                    echo '</tbody></table></div><br>';
                    echo '<div class="clear"></div>'
                    . '<input type="submit" class="gumb" id="dodijeli_moderatora" name="dodijeli_moderatora" value="Dodijeli ulogu moderatora"><br>'
                    . '<div class="clear"></div>';
                    if (isset($_POST["dodijeli_moderatora"])) {
                        $sql = "UPDATE korisnik SET id_uloga=2 WHERE korisnicko_ime='" . $_GET["korisnik"] . "'";
                        $veza->updateDB($sql);
                        $date = date('Y-m-d H:i:s');
                        $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Dodijelio ulogu moderatora korisniku " . $_GET["korisnik"] . ".', '" . $date . "')";
                        $veza->updateDB($sql_dnevnik);
                    }
                    break;
                default:
                    break;
            }
            $veza->zatvoriDB();
            ?>
        </section>
    </body>
</html>
