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
$sql = "SELECT * FROM dnevnik_rada LIMIT $pocni_od, $zapisa_po_stranici";
$rs = $veza->selectDB($sql);
$output .= "<table border='1'>"
        . "<tr><th>Korisnik</th><th>Aktivnost</th><th>Vrijeme</th></tr>";
while ($red = mysqli_fetch_array($rs)) {
    $output .= '<tr><td>' . $red[0] . '</td><td>' . $red[1] . '</td><td>' . $red[2] . '</td></tr>';
}
$output .= '</table><br><div align="center">';
$sql_stranica = "SELECT * FROM dnevnik_rada";
$rs_stranica = $veza->selectDB($sql_stranica);
$ukupno_zapisa = mysqli_num_rows($rs_stranica);
$ukupno_stranica = ceil($ukupno_zapisa / $zapisa_po_stranici);
for ($i = 1; $i <= $ukupno_stranica; $i++) {
    $output .= "<span class='pagination_link' style='cursor:pointer; padding:6px;border:1px solid #ccc;' id='" . $i . "'>" . $i . "</span>";
}
echo $output;

