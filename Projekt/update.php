<?php
require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$id = mysqli_real_escape_string($_POST["value"]);
echo $id;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

