<?php
    //Function to get Usercredentials from db and log in user
    session_start();
    include "../../classes.php";

    if(isset($_POST['UserMailLogin']) && isset($_POST['UserPasswordLogin'])) {

        $user = new user();
        $user->setUserMail($_POST['UserMailLogin']);
        $user->setUserPassword(sha1($_POST['UserPasswordLogin']));

        if($user->userLogin()==true) {
            $_SESSION['UserId'] = $user->getUserId();
            $_SESSION['UserName'] = $user->getUserName();
            $_SESSION['UserMail'] = $user->getUserMail();
            $_SESSION['UserNinja'] = $user->getUserNinja();
            $_SESSION['isloggedin'] = true;
			$_SESSION['login_timestamp'] = time();
			$_SESSION['logoutmessage'] = "Your session expired! Please login again!";
        }
    }
?>