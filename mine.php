<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SVG Twojs</title>
	<script src="jquery.js"></script>
	<script src="two.min.js"></script>
	<style>
		#assets{display: none;}
		body{
			font-family:'Open Sans', sans-serif;
		}
		button{
			/*margin-left: 15%;*/
			/*margin-top: 10%;*/
			position: relative;
			z-index:10000;
			width: 150px;
			height: 40px;
		}
		.faster{margin-left: 0;}
		.clear {
			clear: both;
			display: block;
			overflow: hidden;
			visibility: hidden;
			width: 0;
			height: 0;
		}
		.container{
			margin: 0 auto;
			width: 80%;
		}
		.controls{
			width: 60%;
			margin: 0 auto;
		}
	</style>
</head>
<body>
<div class="container">
	<div id="assets">
		<?= file_get_contents('battery.svg'); ?>
		<?= file_get_contents('commuter.svg'); ?>
		<?= file_get_contents('leftmagnet.svg'); ?>
		<?= file_get_contents('rightmagnet.svg'); ?>
		<?= file_get_contents('coil.svg'); ?>
	</div>
</div>
<div class="clear"></div>
<div class="controls">

	<button class="start">Play</button>
	<button class="stop">Pausa</button>
	<button class="faster">Aumentar velocidad</button>
	<label><input type="checkbox" name="" id="coilRotation"> Rotaci&oacute;n del solenoide</label>
	<label><input type="checkbox" name="" id="fieldLines"> L&iacute;neas de campo</label>
</div>
<script>

	var two = new Two({
		type: Two.Types['svg'],
		fullscreen: false,
		width:1100,
		autostart: false
	}).appendTo($('.container')[0]);
	var coilRotation = false;
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

	// Arrow
	// var arrowHead = two.makePath(,false);

	var arrowsArray = [];

	var arrowHead = new Two.Path([new Two.Anchor(0,0), new Two.Anchor(-2,-2), new Two.Anchor(0,-4)], false);
	arrowHead.scale = 3;
	arrowHead.fill = '#000';
	arrowHead.linewidth = .5;
	arrowHead.translation.y = 5;
	var arrowLine = new Two.Line(0,0,40,0);
	arrowLine.linewidth = 2;
	var arrow = new Two.Group(arrowHead, arrowLine);
	two.add(arrowHead, arrowLine);
	var instance, instancia;
	// arrow.translation.x += 2000;
	var arrowsGroup  = new Two.Group();
	for (var i = 1; i < 10; i++) {
		instance = arrowHead.clone();
		instancia = arrowLine.clone();
		// instance.translation.y += i*20;
		// instancia.translation.y += i*20;
		var arrowGroup = two.makeGroup(instance, instancia);
		arrowsArray.push(arrowGroup);
		arrowsGroup.add(arrowGroup);
	}
	// arrowsGroup.add(arrow);
	two.add(arrowsGroup);
	arrowLine.visible = arrowHead.visible = false;
	var deltax = 0, deltay = 0;
	var it = 0;
	var deltas = [58.246571850031614, 15.592192579060793, 149.52321810415015, 76.16956746205688, 100.490913761314, 22.970735805574805, 165.06488729501143, 72.58823714219034, 188.2410985417664];
	_.map(arrowsArray, function(arrow){
		if(delta < 25){
			arrow.translation.x += deltax;
			arrow.translation.y -= deltay;

		}
		if(delta >= 25 && delta < 50){
			arrow.translation.x += deltax;
			arrow.translation.y += deltay;
		}
		else if(delta >= 50 && delta <= 75){
			arrow.translation.x -= deltax;
			arrow.translation.y += deltay;
		}
		else{
			arrow.translation.x -= deltax;
			arrow.translation.y -= deltay;
		}
		deltax += 25;
		// deltay += 150*Math.pow(-1, it)*Math.random();
		deltay = deltas[it];
		deltas.push(deltay);
		it++;
	});
	arrowsGroup.translation.x += 130;


	// var coilPath = new Two.Ellipse(0, 0, 270, 115);
	// coilPath.noFill().stroke = "#000";
	// coilPath.scale = .5;
	// coilPath.translation.set(50, -95);
	// two.add(coilPath);

	// Coil electron
	var coilElectron =  two.makeCircle(0,0,5);
	coilElectron.noStroke().fill = "#fff800";
	coilElectron.translation.set(175, -185);
	
	// Commuter
	var commuter = two.interpret($('svg.commuter')[0]);
	commuter.visible = true;
	commuter.scale = .6;
	commuter.translation.set(-30, -110);

	// linea del commuter
	var comLine = two.makeRectangle(50,-40, 104, 8);
	comLine.noStroke().fill = "#fff";

	

	// console.log(deltas);
	arrowsGroup.visible = false;

	var defaultSpeed = .09;
	var electronSpeed = 1;
	var right = true, a = 1;
	two.bind('update', function(frameCount){
		// return;
		// var number = frameCount;

		// loopNo = frameCount%321;


	//	-----------------------------------
	//				Electron flow
	//	-----------------------------------
		var e;
		for(var i = 0; i<electrons.length; i++){
			// break;
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


		// Coil electron animation
		if(!coilRotation){
			coilElectron.translation.x += 5*Math.sin(-frameCount/(Math.PI * 8));
			//-76.29836250018285
			// 175.0078461006153
			if(coilElectron.translation.x >= 175.0078461006153)
				right = true;
			if(coilElectron.translation.x <= -76.29836250018285)
				right = false;
			a = right? 1 : -1;
			coilElectron.translation.y =  a*.4*Math.sqrt(Math.pow(126.65,2) - Math.pow(coilElectron.translation.x - 49.65, 2)) - 100;
			// if(isNaN(coilElectron.translation.y)){
			// 	console.log(coilElectron.translation.x);
			// 	console.log(frameCount);
			// 	two.pause();
			// }
		}

		// Arrows
		var timeSpan = 60;
		if(frameCount%timeSpan == 0){
			arrowsGroup.translation.x += timeSpan/2;
			arrowsGroup.opacity = 1;
		}
		if(frameCount%timeSpan > 0){
			arrowsGroup.opacity -= .016;
			arrowsGroup.translation.x -= .5;
		}
		if(coilRotation){		
			comLine.rotation += defaultSpeed;
			coil.rotation += defaultSpeed;
		}
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
		$('#coilRotation').change(function(){
			coilRotation = !coilRotation;
			coilElectron.visible = !coilElectron.visible;
		});
		$('#fieldLines').change(function(){
			if(this.checked){
				arrowsGroup.visible = true;
			}
			else arrowsGroup.visible = false;
		});
	});
</script>
</body>
</html>