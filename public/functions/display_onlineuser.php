<?php
    //Function to display the actual amount of onlineplayers
    include '../../classes.php';
    $onlineState = new user();
    $onlineState->displayOnlineUsers();
?> 