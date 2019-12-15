<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "SELECT o.broj_klikova, o.naslov FROM oglas o JOIN zahtjev_za_kreiranjem_oglasa zzko ON o.id_zahtjev=zzko.id_zahtjev JOIN korisnik k ON zzko.id_korisnik=k.id_korisnik";
$rs = $veza->selectDB($sql);
$statistika = array();
while ($red = mysqli_fetch_array($rs)) {
    $statistika[] = $red;
}
$veza->zatvoriDB();
echo json_encode($statistika);

