<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$zapisa_po_stranici = 5;
$stranica = '';
$output = '';
if (isset($_POST["stranica"])) {
    $stranica = $_POST["stranica"];
} else {
    $stranica = 1;
}

$pocni_od = ($stranica - 1) * $zapisa_po_stranici;
$sql = "SELECT k.korisnicko_ime, o.naslov, o.sadržaj, zzbo.razlog, zzbo.id_oglas, zzbo.id_korisnik, zzbo.id_zahtjev FROM korisnik k JOIN zahtjev_za_blokiranje_oglasa zzbo ON k.id_korisnik=zzbo.id_korisnik JOIN oglas o ON zzbo.id_oglas=o.id_oglas WHERE zzbo.status IS NULL ORDER BY 1 DESC LIMIT $pocni_od, $zapisa_po_stranici";
$rs = $veza->selectDB($sql);
$output .= "<table border='1'>"
        . "<tr><th>Korisnik</th><th>Naslov oglasa</th><th>Sadržaj oglasa</th><th>Razlog</th></tr>";
while ($red = mysqli_fetch_array($rs)) {
    $output .= '<tr><td>' . $red[0] . '</td><td><a href=upravljackaPloca.php?mod=zahtjeviZaBlokiranjeOglasa&id_korisnik=' . $red[5] . '&id_oglas=' . $red[4] . '&id_zahtjev=' . $red[6] . '>' . $red[1] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td></tr>';
}
$output .= '</table><br><div align="center">';
$sql_stranica = "SELECT k.korisnicko_ime, o.naslov, o.sadržaj, zzbo.razlog, zzbo.id_oglas, zzbo.id_korisnik, zzbo.id_zahtjev FROM korisnik k JOIN zahtjev_za_blokiranje_oglasa zzbo ON k.id_korisnik=zzbo.id_korisnik JOIN oglas o ON zzbo.id_oglas=o.id_oglas WHERE zzbo.status IS NULL ORDER BY 1 DESC";
$rs_stranica = $veza->selectDB($sql_stranica);
$ukupno_zapisa = mysqli_num_rows($rs_stranica);
$ukupno_stranica = ceil($ukupno_zapisa / $zapisa_po_stranici);
for ($i = 1; $i <= $ukupno_stranica; $i++) {
    $output .= "<span class='pagination_link' style='cursor:pointer; padding:6px;border:1px solid #ccc;' id='" . $i . "'>" . $i . "</span>";
}
echo $output;

