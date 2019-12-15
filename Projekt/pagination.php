<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$record_per_page = 5;
$page = '';
$output = '';
if (isset($_POST["page"])) {
    $page = $_POST["page"];
} else {
    $page = 1;
}

$start_from = ($page - 1) * $record_per_page;
$sql = "SELECT k.korisnicko_ime, o.naslov, o.sadržaj, zzbo.razlog, zzbo.id_oglas, zzbo.id_korisnik, zzbo.id_zahtjev FROM korisnik k JOIN zahtjev_za_blokiranje_oglasa zzbo ON k.id_korisnik=zzbo.id_korisnik JOIN oglas o ON zzbo.id_oglas=o.id_oglas WHERE zzbo.status IS NULL ORDER BY 1 DESC LIMIT $start_from, $record_per_page";
$rs = $veza->selectDB($sql);
$output .= "<table border='1'>"
        . "<tr><th>Korisnik</th><th>Naslov oglasa</th><th>Sadržaj oglasa</th><th>Razlog</th></tr>";
while ($red = mysqli_fetch_array($rs)) {
    $output .= '<tr><td>' . $red[0] . '</td><td><a href=upravljackaPloca.php?mod=zahtjeviZaBlokiranjeOglasa&id_korisnik=' . $red[5] . '&id_oglas=' . $red[4] . '&id_zahtjev=' . $red[6] . '>' . $red[1] . '</td><td>' . $red[2] . '</td><td>' . $red[3] . '</td></tr>';
}
$output .= '</table><br><div align="center">';
$page_query = "SELECT k.korisnicko_ime, o.naslov, o.sadržaj, zzbo.razlog, zzbo.id_oglas, zzbo.id_korisnik, zzbo.id_zahtjev FROM korisnik k JOIN zahtjev_za_blokiranje_oglasa zzbo ON k.id_korisnik=zzbo.id_korisnik JOIN oglas o ON zzbo.id_oglas=o.id_oglas WHERE zzbo.status IS NULL ORDER BY 1 DESC";
$page_rs = $veza->selectDB($page_query);
$total_records = mysqli_num_rows($page_rs);
$total_pages = ceil($total_records / $record_per_page);
for ($i = 1; $i <= $total_pages; $i++) {
    $output .= "<span class='pagination_link' style='cursor:pointer; padding:6px;border:1px solid #ccc;' id='" . $i . "'>" . $i . "</span>";
}
echo $output;

