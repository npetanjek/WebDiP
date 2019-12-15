<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "SELECT id_vrsta, COUNT(id_vrsta) * (SELECT cijena FROM vrsta_oglasa WHERE id_vrsta_oglasa = id_vrsta) FROM zahtjev_za_kreiranjem_oglasa WHERE STATUS =1 GROUP BY 1  ORDER BY 1 DESC";
$rs = $veza->selectDB($sql);
$statistika = array();
while ($red = mysqli_fetch_array($rs)) {
    $statistika[] = $red;
}
$veza->zatvoriDB();
echo json_encode($statistika);

