<?php
    //Updates the NinjaOutfit of the user NOT WORKING YET
    session_start();
    include '../../classes.php';

    if(isset($_POST['ninjaFit'])){
        $ninjaFit = new user();
        $ninjaFit->setUserName($_SESSION['UserName']);
        $ninjaFit->setUserNinja($_POST['ninjaFit']);
        $ninjaFit->updateNinjafit();
}
?>