<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
if(!empty($_GET["id"])){
    $sql = "UPDATE korisnik SET status = 'aktivan' WHERE id_korisnik=" . $_GET["id"] . "'";
    $rs = $veza->updateDB($sql);
    if(!empty($rs)){
        $message = "Vaš korisnički račun je aktiviran.";
    }
    else{
        $message = "Problem kod aktivacije korisničkog računa!";
    }
}
?>
<html>
    <body>
        <?php if(isset($message)){ ?>
        <div>
            <?php echo $message; ?>
        </div>
        <?php } ?>
    </body>
</html>

