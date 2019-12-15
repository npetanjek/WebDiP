<?php

require './sesija.class.php';
Sesija::kreirajSesiju();
require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "SELECT * FROM vrsta_oglasa WHERE id_moderator=" . $_SESSION["id"];
$rs = $veza->selectDB($sql);
$vrste = array();
while ($red = mysqli_fetch_array($rs)) {
    $vrste[] = $red;
}
$veza->zatvoriDB();
echo json_encode($vrste);

