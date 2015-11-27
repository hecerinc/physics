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
<button style="margin-left:15%; margin-top:20%; position:relative; z-index:10000;">Stahp</button>
<div id="assets">
	<?= file_get_contents('coil.svg'); ?>
</div>
<script>

	var two = new Two({
		// width: 600, l
		// height:600,
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

	two.scene.translation.set(two.width / 2, two.height / 2);

	// Path vertices
	var vertices = [
		[0,0],
		[100,0],
		[120,80],
		[-20,8]
	];

	// Coil
	// var easing = 0.125;
	// var shape = two.interpret($('svg')[0]).center();
	// shape.visible = true;
	// shape.scale = .5;

	var electrones = new Two.Group();
	electrones.add(electrons);
	two.add(electrones); // Add to scene
	two.bind('update', function(frameCount){
		// return;
		// console.log(frameCount);
		// var number = frameCount;

		// console.log(e.translation.y);
		loopNo = frameCount%321;
		for(var i=0; i<electrons.length;i++){
			var e = electrons[i];
			// izq a derecha
			if(e.translation.y == -40){
				if(e.translation.x != 150)
					e.translation.x += 2;
				else{
					e.translation.x += 1;
					e.translation.y += 4;
				}
			}
			// hacia abajo
			else if(e.translation.x >= 150 && e.translation.x <= 190 && e.translation.y < 120){
				e.translation.x += 1;
				e.translation.y += 4;
			}
			// <---------
			else if(e.translation.y >= 120 && e.translation.x != -90){
				e.translation.x -= 2;
			}
			// hacia arriba
			else if(e.translation.y > -40 && e.translation.x >= -90 && e.translation.x <= -50){
				e.translation.x += 1;
				e.translation.y -= 4;
			}
		}
		// if(e)

		// for (var i = 0; i < electrons.length; i++) {
			// if(loopNo > 0 && loopNo <= 40){
			// 	electrons[0].translation.x += 1;
			// 	electrons[0].translation.y -= 4;
			// }
			// if(loopNo > 40 && loopNo <= 140){ // 100 loopNos para moverte los 200 pxs
			// 	electrons[0].translation.x += 2;
			// }
			// if (loopNo > 140 && loopNo <= 180){
			// 	electrons[0].translation.x += 1;
			// 	electrons[0].translation.y += 4;
			// }
			// if(loopNo > 180 && loopNo <= 321){
			// 	electrons[0].translation.x -= 2;
			// }
		// };
		// ._map(electrons, function(electron){
		// });
		// shape.rotation += 0.9;
		// var number = Math.sin(2*Math.PI*(frameCount-60)/100);
		// var osc = Math.sin(- frameCount / (Math.PI * 10));
		// electron.translation.x = frameCount-60 /100;
		// electron.translation.y =  (number?number<0?-1:1:0)*10;
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