<?php

require './sesija.class.php';
Sesija::kreirajSesiju();
require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "SELECT o.`naslov`, o.`sadržaj`, o.`broj_klikova`, zzko.id_vrsta FROM oglas o JOIN zahtjev_za_kreiranjem_oglasa zzko ON o.id_zahtjev=zzko.id_zahtjev WHERE zzko.id_korisnik=" . $_SESSION["id"];
$rs = $veza->selectDB($sql);
$statistika = array();
while ($red = mysqli_fetch_array($rs)) {
    $statistika[] = $red;
}
$veza->zatvoriDB();
echo json_encode($statistika);

