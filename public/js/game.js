	var guid = (function() {

		function s4() {
			return Math.floor((1 + Math.random()) * 0x10000)
					.toString(16)
					.substring(1);
		}
		return function() {
			return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
				s4() + '-' + s4() + s4() + s4();
		};
		})();

	var ninjas = {};

	// Create the canvas
	let canvas = document.createElement("canvas");
	let buffer = document.createElement("canvas").getContext("2d");
	let ctx = canvas.getContext("2d");
	canvas.width = 955;
	canvas.height = 650;
	document.getElementById("game").appendChild(canvas);

	ctx.font="12px pp2";
	ctx.fillStyle = "white";
	

	// Background image
	var bgReady = false;
	var bgImage = new Image();
	bgImage.onload = function () {
		bgReady = true;
	};
	bgImage.src = "images/background.png";

	var spritePaths = {
		ninja: 'images/akali.png',
	};

	const SPRITE_SIZE = 16;
	let controller, display, loop, player, resize;

	let Animation = function(frame_set, delay) {
		this.count = 0;// Counts the number of game cycles since the last frame change.
		this.delay = delay;// The number of game cycles to wait until the next frame change.
		this.frame = 16;// The value in the sprite sheet of the sprite image / tile to display.
		this.frame_index = 0;// The frame's index in the current animation frame set.
		this.frame_set = frame_set;// The current animation frame set that holds sprite tile values.
	};


	var sprite = function(name, path) {
		var that = this;
		this._name = name;
		this._path = path;
		this._image = new Image();
		this._ready = false;
		this._image.onload = function(img) {
			that._ready = true;
		};
		this._image.src = path;
		this._frame_sets = [[0], [0, 1, 2, 3], 			//Standing Still down, Walking down
							[4], [4, 5, 6, 7],			//Standing Still right, Walking right
							[8], [8, 9, 10, 11],		//Standing Still up, Walking up
							[12], [12, 13, 14, 15]]		//Standing Still left, Walking left
	};

	sprite.prototype = {
		width: function() {
			return this._image.width;
		},
		height: function() {
			return this._image.height;
		},
		image: function() {
			return this._image;
		},
		ready: function() {
			return this._ready;
		},
		frameSet: function() {
			return this._frame_sets;
		}
	};

	var spriteCollection = {};
	for(var i in spritePaths) {
		var oSprite = new sprite(i, spritePaths[i]);
		spriteCollection[i] = oSprite;
	}

	var ninja = function(ninjaType) {
		this._type = ninjaType || "ninja";
		this._x = this._y = 0;
		this._speed = 256 //in pixels per second
		this._msg = '';
		this._timer = false;
		this._animation = new Animation(),// You don't need to setup Animation right away.
		this._height = 16,    
		this._width= 16,
		this._x_velocity = 0, 
		this._y_velocity= 0
	};

	ninja.prototype = {
		id: function() {
			return this.ninjaId;
		},
		setId: function(id) {
			this.ninjaId = id;
		},
		playerName: function() {
			return this._playerName;
		},
		setPlayerName: function(playerName) {
			this._playerName = playerName;
		},
		type: function() {
			return this._type;
		},
		setType: function(ninjaType) {
			if(spriteCollection[ninjaType] !== undefined) {
				this._type = ninjaType;
			}
		},
		y: function() {
			return this._y;
		},
		x: function() {
			return this._x;
		},
		speed: function() {
			return this._speed;
		},
		sprite: function() {
			return spriteCollection[this._type];
		},
		ready: function() {
			return this.sprite().ready();
		},
		reset: function(width, height) {
			var sprite = this.sprite()
			// // Throw the Ninja somewhere on the screen randomly
			this._x = sprite.width() + (Math.random() * (width - (sprite.width()*2)));
			this._y = sprite.height() + (Math.random() * (height - (sprite.height()*2)));
		},
		move: function(x, y) {
			this._x = x;
			this._y = y;
		},
		moveX: function(x) {
			this._x = x;
		},
		moveY: function(y) {
			this._y = y;
		},
		say: function(msg) {
			var that = this;
			if(this._timer !== false) {
				clearTimeout(this._timer);
			}
			// Timeout to delete Chatmessage
			setTimeout(function() {
				that._msg = '';
			}, 6 * 1000);
			this._msg = msg;
		},
		said: function() {
			return this._msg;
		},
		serialize: function() {
			return { id: this.ninjaId, y: this._y, x: this._x, ninjaType: this._type, playerName: this._playerName };
		},
		change: function(frame_set, delay = 15) {
		if (this.frame_set != frame_set) {// If the frame set is different:
			this.count = 0;// Reset the count.
			this.delay = delay;// Set the delay.
			this.frame_index = 0;// Start at the first frame in the new frame set.
			this.frame_set = frame_set;// Set the new frame set.
			this.frame = this.frame_set[this.frame_index];// Set the new frame value.
		}
		},
		/* Call this on each game cycle. */
		update:function() {
		this.count ++;// Keep track of how many cycles have passed since the last frame change.
		if (this.count >= this.delay) {// If enough cycles have passed, we change the frame.
			this.count = 0;// Reset the count.
			/* If the frame index is on the last value in the frame set, reset to 0.
			If the frame index is not on the last value, just add 1 to it. */
			this.frame_index = (this.frame_index == this.frame_set.length - 1) ? 0 : this.frame_index + 1;
			this.frame = this.frame_set[this.frame_index];// Change the current frame value.
		}
		},
		animation: function() {
			return this._animation;
		}
	};

	var myNinja = new ninja();

	// Handle keyboard controls
	var keysDown = {    
		left:  { active:false, state:false },
		right: { active:false, state:false },
		up:    { active:false, state:false },
		down:  { active:false, state:false }
	};


	addEventListener("keydown", function (e) {
		keysDown[e.keyCode] = true;
		key_state = (event.type == "keydown") ? true : false;
		//To prevent the page from scrolling by pressing the arrowkeys
		if(["Space","ArrowUp","ArrowDown","ArrowLeft","ArrowRight"].indexOf(e.code) > -1) {
			e.preventDefault();
		}
		if(e.keyCode == 13) {
			sendMessage();
		}
	}, false);

	addEventListener("keyup", function (e) {
		delete keysDown[e.keyCode];
	}, false);

	addEventListener("touchstart", function (e) {
		if(e.touches.item(0) && e.touches.item(0).target.dataset.hasOwnProperty('key')) {
			keysDown[e.touches.item(0).target.dataset.key] = true;
		}
	}, false);

	addEventListener("touchend", function (e) {
		keysDown = {};
	}, false);

	// Update game objects
	var update = function (modifier) {
		var x, y = 0;
		var domove = false;
		if (38 in keysDown) { // Player holding up
			y = myNinja.y() - myNinja.speed() * modifier;
			keysDown.up.state  = key_state;
			keysDown.up.active = key_state;
			if(y <= 0) {
				return;
			}
			myNinja.moveY(y);
			domove = true;
		}
		if (40 in keysDown) { // Player holding down
			y = myNinja.y() + myNinja.speed() * modifier;
			keysDown.down.state  = key_state;
			keysDown.down.active = key_state;
			if(y+32 >= canvas.height) {
				return;
			}
			myNinja.moveY(y);
			domove = true;
		}
		if (37 in keysDown) { // Player holding left
			x = myNinja.x() - myNinja.speed() * modifier;
			keysDown.left.state  = key_state;
			keysDown.left.active = key_state;
			if(x <= 0) {
				return;
			}
			myNinja.moveX(x);
			domove = true;
		}
		if (39 in keysDown) { // Player holding right
			x = myNinja.x() + myNinja.speed() * modifier;
			keysDown.right.state  = key_state;
			keysDown.right.active = key_state;
			if(x+32 >= canvas.width ) {
				return;
			}
			myNinja.moveX(x);
			domove = true;
		}

		if(domove) {
			conn.publish('char_move', myNinja.serialize(), true);
		}

		console.log("left:  " + keysDown.left.state + ", " + keysDown.left.active + "\nright: " + keysDown.right.state + ", " + keysDown.right.active + "\nup:    " + keysDown.up.state + ", " + keysDown.up.active);

	};


	// Draw everything
	var render = function () {
		if (bgReady) {
			ctx.drawImage(bgImage, 0, 0, 955, 650);
		}

		for(var i in ninjas) {
			if (ninjas[i].ready()) {
				if(ninjas[i] === myNinja) {
					continue;
				}
				renderNinja(ninjas[i]);
			}
		}
		// little hack to make sure we stay on top of everyone else
		renderNinja(myNinja);
	};

	function renderNinja(ninja) {
		// Size of Ninja
		ctx.drawImage(ninja.sprite().image(), ninja.x(), ninja.y(), 90, 90);
		// ctx.drawImage(ninja.sprite().image(), ninja.prototype * SPRITE_SIZE, 0, SPRITE_SIZE, SPRITE_SIZE, Math.floor(ninja.x), Math.floor(ninja.y), SPRITE_SIZE, SPRITE_SIZE);

		// Size of Playername
		ctx.fillText(ninja.playerName(), ninja.x() + 5, ninja.y() + 110);
		if(ninja.said() != '') {
			ctx.fillText(ninja.said(), ninja.x() + (50 / 2), ninja.y() - 10);
		}
		
	};


	// The main game loop
	var main = function () {
		var now = Date.now();
		var delta = now - then;

		if (keysDown.down.active) {
			ninja.prototype.change(sprite.prototype.frameSet[1], 15);
			ninja.x_velocity += 0.05;
		}

		if (keysDown.right.active) {
			ninja.prototype.change(sprite.prototype.frameSet[3], 15);
			ninja.x_velocity += 0.05;
		}

		if (keysDown.up.active) {
			ninja.prototype.change(sprite.prototype.frameSet[5], 15);
			ninja.y_velocity -= 2.5;
		}
	
		if (keysDown.left.active) {
			/* To change the animation, all you have to do is call animation.change. */
			ninja.prototype.change(sprite.prototype.frameSet[7], 15);
			ninja.x_velocity -= 0.05;
		}


		/* If you're just standing still, change the animation to standing still. */
		if (!keysDown.down.active) {
		ninja.prototype.change(sprite.prototype.frameSet[0], 20);
		}

		if (!keysDown.right.active) {
			ninja.prototype.change(sprite.prototype.frameSet[2], 20);
		}

		if (!keysDown.up.active) {
			ninja.prototype.change(sprite.prototype.frameSet[4], 20);
		}
		
		if (!keysDown.left.active) {
			ninja.prototype.change(sprite.prototype.frameSet[6], 20);
		}



	
		ninja.y_velocity += 0.25;
	
		ninja.x += ninja.x_velocity;
		ninja.y += ninja.y_velocity;
		ninja.x_velocity *= 0.9;
		ninja.y_velocity *= 0.9;
	
		if (ninja.y + ninja.height > buffer.canvas.height - 2) {
			ninja.y = buffer.canvas.height - 2 - ninja.height;
			ninja.y_velocity = 0;
		}
	
		if (ninja.x + ninja.width < 0) {
			ninja.x = buffer.canvas.width;
		} else if (ninja.x > buffer.canvas.width) {
			ninja.x = - ninja.width;
		}
	
		ninja.prototype.update();

		update(delta / 1000);
		render();

		then = now;

		// Request to do this again ASAP
		requestAnimationFrame(main);
	};

	// Cross-browser support for requestAnimationFrame
	var w = window;
	requestAnimationFrame = w.requestAnimationFrame || w.webkitRequestAnimationFrame || w.msRequestAnimationFrame || w.mozRequestAnimationFrame;

	// Let's play this game!
	var then = Date.now();
	var conn;

	window.onbeforeunload = function(){
		if(conn) {
			conn.publish('char_remove', myNinja.serialize(), true);
		}
	};

	function sendMessage() {
		var message = document.getElementById("message").value;
		if(conn && message) {

			conn.publish('char_msg', { id: myNinja.id(), msg: message });

			myNinja.setType(message.substring(1));
			if(message[0] != "/") {
				myNinja.say(message);
			}
			document.getElementById("message").value = '';
		}
	}

	function startTheGame() {
		let playerName = document.getElementById("playerName").innerText;

		myNinja.setPlayerName(playerName);

		document.getElementById("game-wrapper").style.display = 'block';
		conn = new ab.Session('ws://' + window.location.hostname + ':8181',
			function() {
				conn.subscribe('char_move', function(topic, data){
					if(ninjas[data.id] !== undefined) {
						ninjas[data.id].moveX(parseFloat(data.x));
						ninjas[data.id].moveY(parseFloat(data.y));
					}
				});

				conn.subscribe('char_msg', function(topic, data){
					if(ninjas[data.id] !== undefined) {
						ninjas[data.id].say(data.msg);
						if(data['ninjaType'] !== undefined) {
							ninjas[data.id].setType(data['ninjaType']);
						}
					}
				});

				conn.subscribe('char_add', function(topic, data){
					var myNinja = new ninja();
					myNinja.moveX(parseFloat(data.x));
					myNinja.moveY(parseFloat(data.y));
					myNinja.setId(data.id);
					myNinja.setPlayerName(data.playerName);
					ninjas[data.id] = myNinja;
				});

				conn.subscribe('char_remove', function(topic, data){
					if(ninjas[data.id] !== undefined) {
						delete ninjas[data.id];
					}
				});

				myNinja.reset(canvas.width, canvas.height);
				myNinja.setId(guid());
				ninjas[myNinja.id()] = myNinja;

				main();

				conn.publish('char_add', myNinja.serialize(), true);
				
				conn.call('synchronize').then(function(data) {
					var players = data.players;
					for(var i in players) {
						var current = players[i].lastMove;
						if(current == null || current.id === myNinja.id()) {
							continue;
						}

						var newNinja = new ninja();
						newNinja.moveX(parseFloat(current.x));
						newNinja.moveY(parseFloat(current.y));
						newNinja.setId(current.id);
						newNinja.setType(current.ninjaType);
						newNinja.setPlayerName(current.playerName);
						ninjas[current.id] = newNinja;
					}
				});
			},
			function() {
				console.warn('WebSocket connection closed');
			},
			{'skipSubprotocolCheck': true}
		);
	};

$(function(){

	$(".outfitBtn").click(function(e){
		e.preventDefault();
		let ninjaFit = $('input[name=outfit]:checked', '#ninjaFit').val();
		$( 'input[name=outfit]:checked' ).prop( "checked", false );
		//When btn  get s clicked do:
		$.ajax({
		   type:'POST',
		   url:'../functions/update_ninjafit.php',
		   data:{ninjaFit:ninjaFit},
		   success: function(){
		   }
		})
	 });
  

	 //Interval function to Display Onlineusers
	 setInterval(function(){
		$('.onlineState').load('functions/display_onlineuser.php')
	 },10000);
  
	 $('.onlineState').load('functions/display_onlineuser.php')

	addEventListener("keydown", function (e) {
		keysDown[e.keyCode] = true;
		if(e.keyCode == 32){
			$('#message').val($('#message').val()+' ')
		}
	}, false);


	// Backgroundmusic Control
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