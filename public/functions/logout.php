<?php 
    session_start();
	include "../../classes.php";
	$user = new user();
    $user->userLogout($_SESSION['UserMail']);
	unset($_SESSION['UserId']);
	$_SESSION['logoutmessage'] = "You have been logged out Ninja!!";
	header('location: ../../index.php');
?>