<!DOCTYPE html>
<?php
$cookie_name = "user";
$cookie_value = "Nikola Petanjek";
setcookie($cookie_name, $cookie_value, time() + (86400 * 2), "/"); //86400 = 1 dan
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="css/npetanjek.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src ="js/npetanjek_jquery.js" type="text/javascript">
        </script>
        <title></title>
    </head>
    <body>
        <?php
        if (!isset($_COOKIE[$cookie_name])) {
            echo "<SCRIPT>
                alert('Klikom na gumb \"U redu\" prihvaćate uvjete korištenja.');
            </SCRIPT>";
        }


        //provjera postojanja korisnika u bazi
        
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
                    <li>Lokacije</li>
                    <li>Oglasi</li>
                </ul>
            </nav>
            <div class="horizontalna_linija_maroon"></div>
        </header>
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
                <form novalidate id="form_registracija" name="form_registracija" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                    <label class="label_lijevo" for="ime">Ime: </label>
                    <input class="input_desno" type="text" id="ime" name="ime">
                    <br>
                    <label class="label_lijevo" for="prezime">Prezime: </label>
                    <input class="input_desno" type="text" id="prezime" name="prezime">
                    <br>
                    <label class="label_lijevo" for="korime">Korisničko ime: </label>
                    <input class="input_desno" type="text" id="korime" name="korime" maxlength="15"><span id="kor_rezultat"></span>
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
