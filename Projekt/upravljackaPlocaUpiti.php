<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();

switch ($_GET["mod"]) {
    case 'lista_vrsti':
        $sql = "SELECT * FROM vrsta_oglasa";
        $rs = $veza->selectDB($sql);
        $vrste = array();
        while ($red = mysqli_fetch_array($rs)) {
            $vrste[] = $red;
        }
        $veza->zatvoriDB();
        echo json_encode($vrste);

        break;

    default:
        break;
}
$sql = "SELECT * 
FROM vrste_na_pozicijama
JOIN vrsta_oglasa ON id_pozicija = vrsta_oglasa.id_vrsta_oglasa WHERE vrste_na_pozicijama.id_pozicija='{$_POST["id_pozicija"]}'";
$rs = $veza->selectDB($sql);
$pozicija = array();
while ($red = mysqli_fetch_array($rs)) {
    $pozicija[] = $red;
}
$veza->zatvoriDB();
echo json_encode($pozicija);
