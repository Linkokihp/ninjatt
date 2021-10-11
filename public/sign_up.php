<?php require_once('../config.php') ?>
<?php require_once(ROOT_PATH . '/includes/head_section.php') ?>
    <link rel="stylesheet" href="style/style.css">
    <link href="style/dist/rpgui.css" rel="stylesheet" type="text/css" >
    <title>SignUp to Ninjatt</title>
</head>
<body class="signUp rpgui-content">
    <div id="signUp" class="formDiv rpgui-container framed-grey animate__animated animate__fadeInDown">
        <form autocomplete="off" id="form2" method="post" action="functions/insert_user.php">
            <h2>SignUp Form</h2>
            <table>
                <tr>
                    <td><input type="text" name="UserName" placeholder="Enter your Ninja-Name" required></td>
                </tr>
                <tr>
                    <td><input autocomplete="false" type="email" name="UserMail" placeholder="Enter your Email" required></td>
                </tr>
                <tr>
                    <td><input type="password" name="UserPassword" placeholder="Enter your Password" required></td>
                </tr>
                <tr>
                    <td><button class="signUpBtn" type="submit" value="SignUp">Sign Up</button></td>
                </tr>
                <?php
                    if(isset($_GET['success'])) {
                ?>
                <tr>
                    <td><span style="color: green;">User inserted</span></td>
                </tr>
                <?php   
                    }
                ?>
            </table>
            <h4><a href="../index.php">I'm already a Ninjatter!</a></h4>
        </form>
    </div>
    <script src="style/dist/rpgui.js"></script>
</body>
</html>