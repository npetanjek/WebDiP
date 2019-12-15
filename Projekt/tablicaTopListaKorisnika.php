<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "SELECT k.korisnicko_ime, vo.id_vrsta_oglasa, SUM(vo.cijena) FROM korisnik k JOIN zahtjev_za_kreiranjem_oglasa zzko ON k.id_korisnik=zzko.id_korisnik JOIN vrsta_oglasa vo ON zzko.id_vrsta=vo.id_vrsta_oglasa WHERE zzko.status=1 GROUP BY 1 ORDER BY 3 DESC";
$rs = $veza->selectDB($sql);
$statistika = array();
while ($red = mysqli_fetch_array($rs)) {
    $statistika[] = $red;
}
$veza->zatvoriDB();
echo json_encode($statistika);

