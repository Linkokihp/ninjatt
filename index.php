<?php require_once('config.php') ?>
<?php require __DIR__ . '/vendor/autoload.php'; ?>
<?php require_once(ROOT_PATH . '/includes/head_section.php') ?>
    <link rel="stylesheet" href="public/style/style.css">
    <link href="public/style/dist/rpgui.css" rel="stylesheet" type="text/css" >
    <title>Welcome to Ninjatt</title>
</head>
<?php 
    if (isset($_SESSION['logoutmessage'])) {
        echo '<script type="text/javascript">alert("' . $_SESSION['logoutmessage'] . '");</script>';
        unset($_SESSION['logoutmessage']);
        session_destroy();
    }
?>
<body class="index rpgui-content">
    <div class="formDiv rpgui-container framed-grey animate__animated animate__fadeInDown">
        <form autocomplete="off" id="loginForm" method="post" action="public/functions/user_login.php">
            <h2>Login for Ninjatt</h2>
            <table>
                <tr>
                    <td><input type="Email" name="UserMailLogin" placeholder="Enter your Email" required></td>
                </tr>
                <tr>
                    <td><input type="password" name="UserPasswordLogin" placeholder="Enter your Userpassword" required></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Login"></td>
                </tr>
                <?php
                    if(isset($_GET['error'])) {
                ?>
                <tr>
                    <td><span style="color: red;">Wong credentials! Try again!</span></td>
                </tr>
                <?php   
                    }
                ?>
            </table>
            <h4><a href="public/sign_up.php">Not a Ninjatter yet? Sign up!</a></h4>
        </form>
    </div>
    <script src="public/style/dist/rpgui.js"></script>
</body>
</html>