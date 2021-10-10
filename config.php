<?php
    session_start();

    try{
        $bdd = new PDO("mysql:host=localhost; dbname=ninjatt", "root", "");
        // echo "Connected successfully";
    } catch( Exception $e) {
        die("ERROR : " .$e->getMessage());
    }


    define ('ROOT_PATH', 'C:\xampp\htdocs\ninjatt');
	define('BASE_URL', 'http://localhost/ninjatt');
    
?>