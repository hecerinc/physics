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
	var path = two.makePath(0,0,100,0,120,80,-20,80, false);
	path.scale = 2;
	path.linewidth = 3;
	// path.translation.set(two.width/2, two.height/2);

	// Electron
	var electron = two.makeCircle(0,0,5);
	electron.noStroke().fill = "#fff800";
	// electron.translation = new Two.Vector(-90,120);
	electron.translation.set(-90, 120);
	two.scene.translation.set(two.width / 2, two.height / 2);


	// var scale = two.width > two.height ? two.height / dimensions : two.width / dimensions;
	var easing = 0.125;
	var shape = two.interpret($('svg')[0]).center();
	shape.visible = true;
	shape.scale = .5;
	// shape.translation.set(two.width / 2, two.height / 2);
	// two.update();
	// 
	two.bind('update', function(frameCount){
		// return;
		// console.log(frameCount);
		// var number = frameCount;
		loopNo = frameCount%321;
		if(loopNo > 0 && loopNo <= 40){
			electron.translation.x += 1;
			electron.translation.y -= 4;
		}
		if(loopNo > 40 && loopNo <= 140){ // 100 loopNos para moverte los 200 pxs
			electron.translation.x += 2;
		}
		if (loopNo > 140 && loopNo <= 180){
			electron.translation.x += 1;
			electron.translation.y += 4;
		}
		if(loopNo > 180 && loopNo <= 321){
			electron.translation.x -= 2;
		}
		// if(loopNo > 320)
		// 	loopNo = 0;
		// var number = Math.sin(2*Math.PI*(frameCount-60)/100);
		// var osc = Math.sin(- frameCount / (Math.PI * 10));
		// electron.translation.x = frameCount-60 /100;
		// electron.translation.y =  (number?number<0?-1:1:0)*10;
	}).play();
</script>
</body>
</html>