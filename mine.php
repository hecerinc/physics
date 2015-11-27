<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SVG Twojs</title>
	<script src="jquery.js"></script>
	<script src="two.min.js"></script>
	<style>
		#assets{display: none;}
		button{
			margin-left: 15%;
			margin-top: 10%;
			position: relative;
			z-index:10000;
			width: 200px;
			height: 40px;
		}
		.faster{margin-left: 0;}
	</style>
</head>
<body>
<button class="stop">Stahp</button>
<!-- <button class="start">Play!</button> -->
<button class="faster">Go faster!</button>
<div id="assets">
	<?= file_get_contents('battery.svg'); ?>
	<?= file_get_contents('commuter.svg'); ?>
	<?= file_get_contents('leftmagnet.svg'); ?>
	<?= file_get_contents('rightmagnet.svg'); ?>
	<?= file_get_contents('coil.svg'); ?>
</div>
<script>

	var two = new Two({
		type: Two.Types['svg'],
		fullscreen: true,
		autostart: false
	}).appendTo(document.body);
	
	// Path del electron
	var path = two.makePath(0,0,100,0,120,80,-20,80, false);
	path.scale = 2;
	path.linewidth = 3;

	// Electron
	var electrons = [];

	// Generate electrons
	for (var i = 0; i < 10; i++) {
		var electron = two.makeCircle(0,0,5);
		electron.noStroke().fill = "#fff800";
		electrons.push(electron);
	}
	// Displace electrons
	var delta = 0;
	_.map(electrons, function(electron){
		electron.translation.set(-90 + delta, 120);
		delta += 20;
	});

	// Move the whole scene center
	two.scene.translation.set(two.width / 2, two.height / 2);

	// Path vertices
	/*var vertices = [
		[0,0],
		[100,0],
		[120,80],
		[-20,8]
	];*/

	var easing = 0.125;


	
	// Create electron group
	var electrones = new Two.Group();
	electrones.add(electrons);
	two.add(electrones); // Add to scene


	// Battery
	var battery = two.interpret($('svg.battery')[0]);
	battery.visible = true;
	battery.scale = .6;
	battery.translation.set(-15, 85);

	

	// Left magnet
	var leftMagnet = two.interpret($('svg.leftmagnet')[0]);
	leftMagnet.visible = true;
	leftMagnet.scale = .7;
	leftMagnet.translation.set(-460, -200);

	// Right magnet
	var rightMagnet = two.interpret($('svg.rightmagnet')[0]);
	rightMagnet.visible = true;
	rightMagnet.scale = .7;
	rightMagnet.translation.set(140, -200);

	// Coil 
	var coil = two.interpret($('svg.coil')[0]).center();
	coil.visible = true;
	coil.scale = .7;
	coil.translation.set(50, -96);

	// Commuter
	var commuter = two.interpret($('svg.commuter')[0]);
	commuter.visible = true;
	commuter.scale = .6;
	commuter.translation.set(-30, -110);

	// linea del commuter
	var comLine = two.makeRectangle(50,-40, 104, 8);
	comLine.noStroke().fill = "#fff";

	// Coil electron
	var coilElectron =  two.makeCircle(0,0,5);
	coilElectron.noStroke().fill = "#fff800";
	coilElectron.translation.set(175, -45);

	var coilPath = new Two.Ellipse(0, 0, 270, 115);
	coilPath.noFill().stroke = "#000";
	coilPath.scale = .5;
	coilPath.translation.set(50, -95);
	two.add(coilPath);

	var defaultSpeed = .09;
	var electronSpeed = 1;
	two.bind('update', function(frameCount){
		// return;
		// var number = frameCount;

		// loopNo = frameCount%321;


	//	-----------------------------------
	//				Electron flow
	//	-----------------------------------
		var e;
		for(var i = 0; i<electrons.length; i++){
			break;
			e = electrons[i];

			// -------->
			if(e.translation.y == -40){
				if(e.translation.x != 150)
					e.translation.x += 2*electronSpeed;
				else{
					e.translation.x += 1*electronSpeed;
					e.translation.y += 4*electronSpeed;
				}
			}
			// <---------
			else if(e.translation.y >= 120 && e.translation.x != -90){
				e.translation.x -= 2*electronSpeed;
			}
			// down
			else if(e.translation.x >= 150 && e.translation.x <= 190 && e.translation.y < 120){
				e.translation.x += 1*electronSpeed;
				e.translation.y += 4*electronSpeed;
			}
			// up
			else if(e.translation.y > -40 && e.translation.x >= -90 && e.translation.x <= -50){
				e.translation.x += 1*electronSpeed;
				e.translation.y -= 4*electronSpeed;
			}
		}

		// coil.rotation += defaultSpeed;
		// comLine.rotation += defaultSpeed;
		// coilElectron.translation.x = frameCount%5;
		coilElectron.translation.x += 5*Math.sin(-frameCount/(Math.PI * 8));
		// console.log(coilElectron.translation.x);
		//-76.29836250018285
		// 175.0078461006153
		if(frameCount%160> 0 && frameCount%160  <= 80)
			coilElectron.translation.y =  Math.sqrt(Math.pow(125.65,2) - Math.pow(coilElectron.translation.x - 49.65, 2));
			// coilElectron.translation.y = Math.pow(.5*coilElectron.translation.x - 20, 2)/100 - 130;
		else
			coilElectron.translation.y =  - Math.sqrt(Math.pow(125.65,2) - Math.pow(coilElectron.translation.x - 49.65, 2));
			// coilElectron.translation.y = - Math.pow(.5*coilElectron.translation.x - 15, 2)/100 - 40;
		
		// coilElectron.translation.y += 2*Math.sin(1*(-frameCount/(Math.PI*8)));
	}).play();
	// two.update();
	// function drag(e){
	// 	var pct = e.clientX / two.width;
	// 	coilPath.getPointAt(pct, coilElectron.translation);
	// 	coilElectron.translation.addSelf(coilPath.translation);
	// }
	// $(window).bind('mousemove', drag);
	$(function(){
		$('button.faster').prop('disabled', false);
		$('button.stop').click(function(){
			console.log("Stahp");
			two.pause();
		});
		$('button.start').click(function(){
			console.log('Play :)');
			two.play();
		});
		$('button.faster').click(function(){
			console.log('Faster!');
			defaultSpeed = .9;
			electronSpeed = 2;
			$(this).prop('disabled', true);
		});
	});
</script>
</body>
</html>