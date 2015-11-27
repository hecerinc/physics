<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SVG Twojs</title>
	<script src="jquery.js"></script>
	<script src="two.min.js"></script>
	<style>#assets{display: none;}</style>
</head>
<body>
<button style="margin-left:15%; margin-top:20%; position:relative; z-index:10000; width:200px; height:40px;">Stahp</button>
<div id="assets">
	<?= file_get_contents('coil.svg'); ?>
</div>
<script>

	var two = new Two({
		type: Two.Types['svg'],
		fullscreen: true,
		autostart: true
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

	// Coil --------------------------------------------
	/*var easing = 0.125;
	var coil = two.interpret($('svg')[0]).center();
	coil.visible = true;
	coil.scale = .5;*/

	// Create electrong group
	var electrones = new Two.Group();
	electrones.add(electrons);
	two.add(electrones); // Add to scene


	two.bind('update', function(frameCount){
		// return;
		// var number = frameCount;

		// loopNo = frameCount%321;


	//	-----------------------------------
	//				Electron flow
	//	-----------------------------------
		var e;
		
		for(var i=0; i<electrons.length; i++){

			e = electrons[i];

			// -------->
			if(e.translation.y == -40){
				if(e.translation.x != 150)
					e.translation.x += 2;
				else{
					e.translation.x += 1;
					e.translation.y += 4;
				}
			}
			// <---------
			else if(e.translation.y >= 120 && e.translation.x != -90){
				e.translation.x -= 2;
			}
			// down
			else if(e.translation.x >= 150 && e.translation.x <= 190 && e.translation.y < 120){
				e.translation.x += 1;
				e.translation.y += 4;
			}
			// up
			else if(e.translation.y > -40 && e.translation.x >= -90 && e.translation.x <= -50){
				e.translation.x += 1;
				e.translation.y -= 4;
			}
		}

		// coil.rotation += 0.9;

	}).play();


	$(function(){
		$('button').click(function(e){
			e.preventDefault();
			console.log("Stahp");
			two.pause();
		});
	});
</script>
</body>
</html>