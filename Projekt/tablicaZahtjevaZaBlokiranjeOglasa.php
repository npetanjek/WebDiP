<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "SELECT k.korisnicko_ime, o.naslov, o.sadržaj, zzbo.razlog, zzbo.id_oglas, zzbo.id_korisnik FROM korisnik k JOIN zahtjev_za_blokiranje_oglasa zzbo ON k.id_korisnik=zzbo.id_korisnik JOIN oglas o ON zzbo.id_oglas=o.id_oglas WHERE zzbo.status IS NULL";
$rs = $veza->selectDB($sql);
$zahtjevi = array();
while ($red = mysqli_fetch_array($rs)) {
    $zahtjevi[] = $red;
}
$veza->zatvoriDB();
echo json_encode($zahtjevi);

