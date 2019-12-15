<!DOCTYPE html>
<?php
/* if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on") {
  header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  exit();
  } */
?>
<?php
$cookie_name = "user";
$cookie_value = "Nikola Petanjek";
setcookie($cookie_name, $cookie_value, time() + (86400 * 2), "/"); //86400 = 1 dan
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <link href="css/npetanjek.css" rel="stylesheet" type="text/css">
        <link href="css/npetanjek_prilagodbe.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="js2/npetanjek_validacija_korisnika.js"></script>
        <title>Poslovni prostor</title>
    </head>
    <body>
        <?php
        if (!isset($_COOKIE[$cookie_name])) {
            echo "<SCRIPT>
                alert('Klikom na gumb \"U redu\" prihvaćate uvjete korištenja.');
            </SCRIPT>";
        }

        require './sesija.class.php';

        //provjera postojanja korisnika u bazi
        require './baza.class.php';
        $ime = $prezime = $korime = "";
        if (isset($_POST["registracija"])) {
            session_start();
            $greska = "";
            $code = $_SESSION['cap_code'];
            $user = $_POST['captcha'];
            if ($code !== $user) {
                $greska .= "Captcha je neispravan. <br>";
            }
            $ime = $_POST["ime"];
            $prezime = $_POST["prezime"];
            $korime = $_POST["korime"];
            $lozinka1 = $_POST["lozinka1"];
            $lozinka2 = $_POST["lozinka2"];
            //Svi podaci uneseni i ne sadrže '!?#
            foreach ($_POST as $key => $value) {
                if (empty($value)) {
                    $greska .= "$key nije unesen. <br>";
                }
            }
            if (preg_match("/['?#!]/", $ime)) {
                $greska .= "Ime ima nedozvoljen znak. <br>";
            }
            if (preg_match("/['?#!]/", $prezime)) {
                $greska .= "Prezime ima nedozvoljen znak. <br>";
            }
            if (preg_match("/['?#!]/", $korime)) {
                $greska .= "Korisničko ime ima nedozvoljen znak. <br>";
            }
            if (preg_match("/['?#!]/", $lozinka1)) {
                $greska .= "Lozinka ima nedozvoljen znak. <br>";
            }
            if (preg_match("/['?#!]/", $lozinka2)) {
                $greska .= "Potvrda lozinke ima nedozvoljen znak. <br>";
            }
            //email ispravnog formata?
            $email = $_POST["mail"];
            if (!preg_match("/^[a-z\d]+\.{0,}[a-z\d]+@[a-z\d]+\.[a-z\d]{2,}$/", $email)) {
                $greska .= "Format e-maila nije ispravan! <br>";
            }
            //Lozinka i potvrda lozinke isti?
            if ($lozinka1 != $lozinka2) {
                $greska .= "Lozinke se ne podudaraju! <br>";
            }
            if (strlen($korime) < 3) {
                $greska .= "Korisnicko ime mora sadržavati barem 3 slova. <br>";
            }
            if (strlen($lozinka1) < 6) {
                $greska .= "Lozinka mora sadržavati barem 6 znakova. <br>";
            }
            if (empty($greska)) {
                $veza = new Baza();
                $veza->spojiDB();
                //4.a.i.4. email i korisnicko ime zauzeto?
                $sql = "select * from korisnik where korisnicko_ime='" . $korime . "' and email='" . $email . "'";
                $rs = $veza->selectDB($sql);
                $korisnik_postoji = false;
                while ($redak = $rs->fetch_array()) {
                    if ($redak) {
                        $korisnik_postoji = TRUE;
                    }
                }
                if ($korisnik_postoji == TRUE) {
                    $greska .= "Korisnik s istim korisničkim imenom ili emailom već postoji!";
                }
                //4.a.ii.1. Kriptiranje lozinke i zapisivanje novog korisnika u bazu
                $salt = sha1(time());
                $kriptirano = sha1($salt . "--" . $lozinka1);
                $hash = md5(rand(0, 1000));
                $datum_registracije = date('Y-m-d H:i:s');
                $sql = "insert into korisnik " . "(hash, korisnicko_ime, lozinka, ime, prezime, email, kriptirana_lozinka, datum_registracija)" .
                        "values('$hash','$korime','$lozinka1', '$ime', '$prezime', '$email', '$kriptirano', '$datum_registracije')";
                if ($veza->pogreskaDB()) {
                    echo "Problem kod upita na bazu podataka!";
                    exit;
                }
                if (empty($greska)) {
                    $veza->updateDB($sql);
                    $mail_to = $email;
                    $subject = "Aktivacija korisnickog racuna";
                    $message = $korime . ", hvala na Vašoj registraciji, molimo, kliknite na sljedeći link kako biste aktivirali svoj korisnički račun:"
                            . "http://barka.foi.hr/WebDiP/2017_projekti/WebDiP2017x114/verify.php?email=$email&hash=$hash";
                    $headers = "From:Admin@barka.foi.hr" . "\r\n";
                    mail($mail_to, $subject, $message, $headers);
                    $date = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO dnevnik_rada VALUES ('" . $korime . "', 'Registracija korisničkog računa', '" . $date . "')";
                    $veza->updateDB($sql);
                }

                $veza->zatvoriDB();
            }
        }
        if (isset($_POST["prijava"])) {
            $veza = new Baza();
            $veza->spojiDB();
            $sql = "UPDATE korisnik SET broj_neuspj_prijava = broj_neuspj_prijava+1 WHERE korisnicko_ime='" . $_POST["korimeprijava"] . "'";
            $veza->updateDB($sql);
            $sql_neuspj_prijave = "SELECT broj_neuspj_prijava FROM korisnik WHERE korisnicko_ime='" . $_POST["korimeprijava"] . "'";
            $rs = $veza->selectDB($sql_neuspj_prijave);
            $rez = mysqli_fetch_array($rs);
            $broj_neuspj_prijava = $rez[0];
            if ($broj_neuspj_prijava >= 3) {
                $sql_zakljucaj = "UPDATE korisnik SET aktivan = 0 WHERE korisnicko_ime='" . $_POST["korimeprijava"] . "'";
                $veza->updateDB($sql_zakljucaj);
                $date = date('Y-m-d H:i:s');
                $sql = "INSERT INTO dnevnik_rada VALUES ('" . $_POST["korimeprijava"] . "', 'Postignut maksimalan broj promašaja; korisnički račun je zaključan.', '" . $date . "')";
                $veza->updateDB($sql);
                echo '<script type="text/javascript">alert("Premašili ste broj neuspješnih prijava! Vaš račun je sada zaključan.");</script>';
            }
            $veza->zatvoriDB();
            //var_dump($_POST);
            $greska = "";
            //4.b.i. Uspješna prijava?
            foreach ($_POST as $key => $value) {
                if (empty($value)) {
                    $greska .= "$key nije unesen. <br>";
                }
                if (strpos($value, "?") || strpos($value, "'") || strpos($value, "!") || strpos($value, "#")) {
                    $greska .= "$key ima nedozvoljen znak. <br>";
                }
            }
            if (empty($greska)) {
                $veza = new Baza();
                $veza->spojiDB();
                $korimeprijava = $_POST["korimeprijava"];
                $lozinkaprijava = $_POST["lozinkaprijava"];
                $salt = sha1(time());
                $kriptirano = sha1($salt . "--" . $lozinkaprijava);
                if (sha1($salt . "--" . $lozinkaprijava) == $kriptirano)
                    ;
                $sql = "SELECT * FROM `korisnik` WHERE `korisnicko_ime` = '$korimeprijava' AND `lozinka` = '$lozinkaprijava' AND aktivan=1";
                $rezultat = $veza->selectDB($sql);
                $prijavljen = false;
                while ($red = mysqli_fetch_array($rezultat)) {
                    if ($red) {
                        $prijavljen = TRUE;
                        $tip = $red["id_uloga"];
                        $id = $red["id_korisnik"];
                    }
                }
                if ($prijavljen) {
                    Sesija::kreirajKorisnika($korime, $tip, $id);
                    $veza = new Baza();
                    $veza->spojiDB();
                    $sql = "UPDATE korisnik SET broj_neuspj_prijava = 0 WHERE korisnicko_ime='" . $_POST["korimeprijava"] . "'";
                    $veza->updateDB($sql);
                    $date = date('Y-m-d H:i:s');
                    $sql2 = "INSERT INTO dnevnik_rada VALUES ('" . $_POST["korimeprijava"] . "', 'Prijava', '" . $date . "')";
                    $veza->updateDB($sql2);
                    $veza->zatvoriDB();
                    echo "Prijavljeni ste!";
                    $cookie_name = "user";
                    $cookie_value = $korimeprijava;
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 2), "/");
                    header("Location:index.php");
                } else {
                    $date = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO dnevnik_rada VALUES ('" . $_POST["korimeprijava"] . "', 'Neuspješna prijava', '" . $date . "')";
                    $veza->updateDB($sql);
                    echo "Niste prijavljeni!";
                }
                $veza->zatvoriDB();
            }
        }
        if (isset($_POST["zaboravljena_lozinka"])) {
            $korimeprijava = $_POST["korimeprijava"];
            $mail = "";
            $veza = new Baza();
            $veza->spojiDB();
            $date = date('Y-m-d H:i:s');
            $sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_POST["korimeprijava"] . "', 'Zaboravljena lozinka', '" . $date . "')";
            $veza->updateDB($sql_dnevnik);
            $nova_lozinka = randomPassword();
            //echo $nova_lozinka;
            $sql = "SELECT email FROM korisnik WHERE korisnicko_ime='$korimeprijava'";
            $rs = $veza->selectDB($sql);
            while ($red = mysqli_fetch_array($rs)) {
                if ($red) {
                    $mail = $red["email"];
                }
            }
            //echo $mail;
            $mail_to = $mail;
            $subject = "Zaboravljena lozinka";
            $message = "Poštovani $korimeprijava, Vaša nova lozinka je: $nova_lozinka";
            $headers = "From:Admin@barka.foi.hr" . "\r\n";
            mail($mail_to, $subject, $message, $headers);
            $salt = sha1(time());
            $kriptirano = sha1($salt . "--" . $nova_lozinka);
            $sql_update_pw = "UPDATE korisnik SET lozinka='$nova_lozinka', kriptirana_lozinka='$kriptirano' WHERE korisnicko_ime='$korimeprijava'";
            $veza->updateDB($sql_update_pw);
            $veza->zatvoriDB();
        }

        function randomPassword() {
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return implode($pass); //turn the array into a string
        }
        ?>
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
        <div id="greske">
            <?php
            if (isset($greska)) {
                echo "<h1>Greške:</h1>";
                echo $greska;
            }
            ?>
        </div>
        <?php
        if (isset($_GET["mod"]) && ($_GET["mod"]) == "prijava") {
            ?>
            <br>
            <div class="horizontalna_linija_maroon"></div>
            <div class="podnaslov">
                Prijava
            </div>
            <div class="horizontalna_linija_maroon"></div>
            <section>
                <form novalidate id="form_prijava" name="form_prijava" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <label class="label_lijevo" for="korimeprijava">Korisničko ime: </label>
                    <input class="input_desno" type="text" id="korimeprijava" name="korimeprijava">
                    <br>
                    <label class="label_lijevo" for="lozinkaprijava">Lozinka: </label>
                    <input class="input_desno" type="text" id="lozinkaprijava" name="lozinkaprijava">
                    <br>
                    <div class="clear"></div>
                    <input type="submit" class="gumb" id="prijava" name="prijava" value="Prijava">
                    <input type="submit" class="gumb" id="zaboravljena_lozinka" name="zaboravljena_lozinka" value="Zaboravljena lozinka">
                </form>
            </section>
            <?php
        }
        ?>
        <?php
        if (isset($_GET["mod"]) && ($_GET["mod"]) == "registracija") {
            ?>
            <br>
            <div class="horizontalna_linija_maroon"></div>
            <div class="podnaslov">
                Registracija
            </div>
            <div class="horizontalna_linija_maroon"></div>
            <section>
                <form novalidate id="form_registracija" name="form_registracija" method="post" action="prijavaRegistracija.php?mod=registracija">
                    <label class="label_lijevo" for="ime">Ime: </label>
                    <input class="input_desno" type="text" id="ime" name="ime" value="<?php echo $ime ?>">
                    <br>
                    <label class="label_lijevo" for="prezime">Prezime: </label>
                    <input class="input_desno" type="text" id="prezime" name="prezime" value="<?php echo $prezime ?>">
                    <br>
                    <label class="label_lijevo" for="korime">Korisničko ime: </label>
                    <input class="input_desno" type="text" id="korime" name="korime" maxlength="15" value="<?php echo $korime ?>">
                    <br>
                    <label class="label_lijevo" for="mail">e-mail adresa: </label>
                    <input class="input_desno" type="email" id="mail" name="mail">
                    <br>
                    <label class="label_lijevo" for="lozinka1">Lozinka: </label>
                    <input class="input_desno" type="password" id="lozinka1" name="lozinka1">
                    <br>
                    <label class="label_lijevo" for="lozinka2">Ponovi lozinku: </label>
                    <input class="input_desno" type="password" id="lozinka2" name="lozinka2">
                    <br>
                    <div class="clear"></div>
                    <br>
                    <img src="captcha.php">
                    <br>
                    <label class="label_lijevo" for="captcha">Captcha: </label>
                    <input class ="input_desno" type="text" name="captcha">

                    <div class="clear"></div>
                    <input type="submit" class="gumb" id="registracija" name="registracija" value="Registracija">
                    <a class="gumb" href="prijavaRegistracija.php?mod=prijava">Prijava</a>
                    <br>
                </form>
            </section>
            <?php
        }
        ?>
    </body>
</html>
