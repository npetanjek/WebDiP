<?php

require '../baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "SELECT `korisnicko_ime`, `lozinka`, `ime`, `prezime`, `email` FROM `korisnik`";
$rs = $veza->selectDB($sql);
while ($red = mysqli_fetch_array($rs)){
    echo '<!DOCTYPE html>'
    . '<html>'
            . '<head>'
            . '<meta charset="utf-8"></head></html>';
            echo '<p>Korisničko ime: ' . $red[0] . '</p>';
            echo '<p>Lozinka: ' . $red[1] . '</p>';
            echo '<p>Ime: ' . $red[2] . '</p>';
            echo '<p>Prezime: ' . $red[3] . '</p>';
            echo '<p>e-mail: ' . $red[4] . '</p>';
}
$veza->zatvoriDB();

