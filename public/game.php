<?php require_once('../config.php') ?>
<?php require '../vendor/autoload.php'; ?>
<?php require_once(ROOT_PATH . '/includes/head_section.php') ?>
		<meta charset="utf-8">
		<title>Ninjatt</title>
		<link rel="stylesheet" href="style/style.css">
		<link href="style/dist/rpgui.css" rel="stylesheet" type="text/css" >
	</head>
<?php
	$session_laufzeit = 500*60;  //5*60 5 Minutes
	$localtime = time();
	include "../classes.php";

	if( isset($_SESSION['isloggedin'])){
		if($_SESSION['isloggedin'] != true || ($_SESSION['login_timestamp'] + $session_laufzeit) < $localtime) {
			$user = new user();
			$user->userLogout($_SESSION['UserMail']);
			header('location: functions/logout.php');
			exit;
		};
	}	else{
		$_SESSION['logoutmessage'] = "You must login first Ninja!!";
		header('location: functions/index.php');
		exit;
	};
?>
<body onload="startTheGame()" class="gamePage rpgui-content">
	<header class="animate__animated animate__fadeInDown">
		<button id="mute"><i class="mute fas fa-volume-up"></i></button>
		<h1 class='welcome_msg'>Hey <span id="playerName" style= "color: red;"><?php echo $_SESSION['UserName']?></span>! Welcome to Phil's Ninjatt!</h1>
		<a class="logout" href="<?php echo BASE_URL . '/public/functions/logout.php'; ?>" class="rpgui-button">Sign out</a>	
	</header>
	
	<main>
		<!-- USER ONLINESTATE -->
		<div class="onlineStateList rpgui-container framed-grey animate__animated animate__fadeInLeft">
			<p>Ninjatter Online:</p>
			<ul class="onlineState">
			</ul>
		</div>

		<!-- GAMEWINDOW -->
		<div id="game-wrapper" class="rpgui-container framed-grey animate__animated animate__fadeInUp" style="display: none;">
			<div id="game" class="animate__animated animate__zoomIn animate__delay-1s"></div>
			<div id="chat" class="form-inline">
				<label>Message:</label>
				<input id="message" type="text" name="message" class="form-control" />
				<button type="submit" id="sendMsg" value="Send" onclick="sendMessage();" class="sendBtn">Send Message</button>
			</div>
		</div>
		
		<!-- NinjaOutfit -->
		<div class="ninjaFit rpgui-container framed-grey animate__animated animate__fadeInRight">
			<p>Select your Ninjatt-Outfit</p>
			<form id="ninjaFit" method="POST">
				<input class="rpgui-radio golden" name="outfit" type="radio" value="default"><label>Default</label>
				<input class="rpgui-radio golden" name="outfit" type="radio" value="red"><label>Red</label>
				<input class="rpgui-radio golden" name="outfit" type="radio" value="blue"><label>Blue</label>
				<input class="rpgui-radio golden" name="outfit" type="radio" value="green"><label>Green</label>
				<input class="rpgui-radio golden" name="outfit" type="radio" value="yellow"><label>Yellow</label>
				<input class="rpgui-radio golden" name="outfit" type="radio" value="pink"><label>Pink</label>
				<button type="submit" name="saveRadio" class="outfitBtn">Save Ninjatt-Fit</button>
			</form>
		</div>
	</main>

	<!-- Backgroundaudio -->
	<audio id="backgroundMusic" src="audio/music.mp3" autoplay>
		<p>If you are reading this, it is because your browser does not support the audio element.     </p>
	</audio>

	<script>
		$(function(){
			$("#backgroundMusic").prop("volume", 0.1);

			$('#mute').click(function() {
				$('.mute').toggleClass('fa-volume-up fa-volume-mute')
				
				if( $("audio").prop('muted') ) {
					$("audio").prop('muted', false);
				} else {
					$("audio").prop('muted', true);
				}
			});
		});

	</script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="js/autobahn.min.js"></script>
	<script src="js/game.js"></script>
	<script src="style/dist/rpgui.js"></script>
</body>
</html>
