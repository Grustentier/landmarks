<html>

<head><title></title>
<meta charset="utf-8">
<link rel="stylesheet" href="resources/css/style.css">
<link rel="stylesheet" href="resources/css/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="resources/css/dropzone.css">
<link rel="stylesheet" href="resources/css/croppie/croppie.css">
<script type="text/javascript" src="resources/js/jquery/jquery-3.3.1.js"></script>
<script src="resources/js/jquery-ui/jquery-ui.js"></script>
<script src="resources/js/dropzone/dropzone.js"></script>
<script src="resources/js/chart/Charts.js"></script>
<script src="resources/js/chart/Chart.bundle.js"></script>
<script src="resources/js/chart/Chart.utils.js"></script>
<script src="resources/js/croppie/croppie.js"></script>
<script src="resources/js/system.js"></script> 
<script src="resources/js/colors.js"></script> 
  
</head>

<body>
<div id="menu">
<div id="MenuEntry1" onclick="$('html, body').stop().animate({'scrollTop': $('#descriptionContainer').offset().top-50}, 500, 'swing');">About</div>
<div id="MenuEntry2" onclick="$('#measurementContainer').show('slow',function(){$('#resultContainer').show(); $('html, body').stop().animate({'scrollTop': $('#measurementContainer').offset().top-50}, 500, 'swing');});">Measure</div>
<div id="MenuEntry3" onclick="$('html, body').stop().animate({'scrollTop': $('#resultContainer').offset().top-50}, 500, 'swing');">Results</div>
</div>
<div id="descriptionContainer"><div>
<table>
<tr><td style="width: 100px;background: url('resources/images/face.png') no-repeat center center transparent;"></td><td>
Mithilfe dieser App K&ouml;nnen Sie prüfen, wie ähnlich zwei oder mehrere Gesichter zueinander sind. 
Die Grundlage daf&uuml;r bilden sogenannte Gesichtslandmarken. &Uuml;ber diese können korrespondierende Punkte spezifischer Gesichtsmerkmale markiert werden. 
Die Landmarken weisen dann untereinander unterschiedliche Distanzen zueinander auf. 
Die Darstellung unterschiedlicher Distanzverhältnisse kann abschließend einen Überblick darüber verschaffen, inwiefern
unterschiedliche Gesichter, geringe Distanzen und somit große Ähnlichkeiten in Ihren Gesichtsmerkmalen aufweisen.
</td></tr>
<tr><td style="width: 100px;background: url('resources/images/privacy.png') no-repeat center center transparent;"></td><td>
Von viel größerer Bedeutung für den Anwender ist das Speichern der in dieser App verarbeiteten Daten.  
An dieser Stelle wird versichert, dass alle Bilder die über eine DropZone o.Ä. für die App zur Verfügung gestellt werden, 
lediglich für die Darstellung im Browser vorgesehen sind, um die Funktion der App zu ermöglichen. 
Eine Speicherung der Bilder im Backend erfolgt somit nicht.
Genau so verhält sich die App im Umgang mit personenspezifischen Positionen einzelner Landmarken.
Sie als Nutzer können frei entscheiden, inwiefern Sie Landmarkenpositionen der Wissenschaft zut Verfügung stellen möchten.
Dabei würden lediglich die Positionen der Landmarken gespeichert und somit keinerlei Informationen dar&uuml;ber, welche auf die Identit&auml;t einer
Person zurückschließen lässt.
</td></tr>
</table>

</div>
<div style="text-align: center;margin-top: 60px;">
<button id="startMeasurement" class="myBigButton" style=" background:MediumSeaGreen">Start</button>
</div>
</div>
<div id="measurementContainer">
	<button id="newMeasurement" class="myButton" style="margin-top: -47px;position: absolute;background:MediumSeaGreen">New Landmark Positioning</button>
	<div id="tabs"> <ul> </ul> </div>
</div>
<div id="resultContainer">
 
<!--img src="resources/images/arrow.png" style="width: 130px;padding: 20px;"/-->
<br>
<button id='process' class='myBigButton'>Update Results</button>
 
<br><br>
<div id="canvasContainer"><canvas id='canvas'></canvas></div>
 
<button id='saveData' class='myBigButton' style="margin-top: 40px">Save Data</button>
<div id="console"></div>
</div>





<script>


 

$( document ).ready(function() {
	 
	$("#tabs").tabs();
	createNewTab();
	$( document ).tooltip();
	
	var ctx = document.getElementById('canvas').getContext('2d');	
	window.myLine = new Chart(ctx, config);

	$("#startMeasurement").click(function(){
			$(this).hide("slow"); 
			$('#measurementContainer').show('slow',function(){
				$('#MenuEntry2').show("slow"); 
				$('#MenuEntry3').show("slow"); 
				$('#resultContainer').show(); 
				$('html, body').stop().animate({
					'scrollTop': $('#measurementContainer').offset().top-50}, 500, 'swing');
				
			});
	});
	
	$("#newMeasurement").click(function(){ createNewTab();});

	$( "#tabs" ).tabs({ active: 0 });
	$("#process").click(function(){   
		RESULT_JSON = [];
		while (window.myLine.data.datasets.length && window.myLine.data.datasets.length > 0) { window.myLine.data.datasets.pop();}		 
		
		var tabCount = $('#tabs >ul >li').length;		
		for(i = 1; i < tabCount + 1; i++){
			 
			$("#tabs ul li a[href='#tabs-"+i+"']").trigger("click"); 
			b1 = $("#b1-"+i);b2 = $("#b2-"+i);b5 = $("#b5-"+i);a6 = $("#a6-"+i);a7 = $("#a7-"+i);a8 = $("#a8-"+i);a9 = $("#a9-"+i);a10 = $("#a10-"+i);a11 = $("#a11-"+i);a12 = $("#a12-"+i);a13 = $("#a13-"+i);a14 = $("#a14-"+i);a15 = $("#a15-"+i);a16 = $("#a16-"+i);a18 = $("#a18-"+i);a20 = $("#a20-"+i);

			var a6_a11 = euklidDistance(a6.offset(),a11.offset()); 
			var a7_a10 = euklidDistance(a7.offset(),a10.offset()); 
			var a8_a9 = euklidDistance(a8.offset(),a9.offset());
			var a12_a14 = euklidDistance(a12.offset(),a14.offset());
			var a15_a16 = euklidDistance(a15.offset(),a16.offset());
			var a18_a20 = euklidDistance(a18.offset(),a20.offset());
			var b1_a13 = euklidDistance(b1.offset(),a13.offset());			
			var b1_b2 = euklidDistance(b1.offset(),b2.offset());
			var b1_b5 = euklidDistance(b1.offset(),b5.offset());

			var b1_b2_a6_a11 = b1_b2 / a6_a11;
			var b1_b5_a6_a11 = b1_b5 / a6_a11;
			var a12_a14_a15_a16 = a12_a14 / a15_a16;
			var a12_a14_b1_a13 = a12_a14 / b1_a13;
			var a12_a14_b1_b5 = a12_a14 / b1_b5;			
			var a15_a16_a6_a11 =  a15_a16 / a6_a11;
			var a15_a16_a7_a10 =  a15_a16 / a7_a10;			
			var a8_a9_a15_a16 = a8_a9 / a15_a16;
			var a8_a9_b1_a13 = a8_a9 / b1_a13;
			var a8_a9_b1_b5 = a8_a9 / b1_b5;			
			var a7_a10_b1_b5 = a7_a10 / b1_b5;			 
			var b1_a13_a7_a10 = b1_a13 / a7_a10;
			var b1_a13_b1_b5 = b1_a13 / b1_b5;
			var b1_a13_a6_a11 = b1_a13 / a6_a11;

			var a12_a14_a18_a20 = a12_a14 / a18_a20;
			var a15_a16_a18_a20 = a15_a16 / a18_a20; 
			var a6_a11_a18_a20 = a6_a11 / a18_a20; 
			var a8_a9_a18_a20 = a6_a11 / a8_a9;
			var a7_a10_a18_a20 = a7_a10 / a8_a9;


			window.myLine.data.datasets.push({
				  label: 'Person-' + i, 
				  fill: false,
				  borderColor: colors[i],
				  data: [b1_b2_a6_a11,b1_b5_a6_a11,a12_a14_a15_a16,a12_a14_b1_a13,a12_a14_b1_b5,a15_a16_a6_a11,a15_a16_a7_a10,a8_a9_a15_a16,a8_a9_b1_a13,a8_a9_b1_b5,a7_a10_b1_b5,b1_a13_a7_a10,b1_a13_b1_b5,b1_a13_a6_a11,a12_a14_a18_a20,a15_a16_a18_a20,a6_a11_a18_a20,a8_a9_a18_a20,a7_a10_a18_a20]
			}); 

		 
			var personJSON = {
								"landmarks":{
									"a6":[a6.offset().left,a6.offset().top],
									"a7":[a7.offset().left,a7.offset().top],
									"a8":[a8.offset().left,a8.offset().top],
									"a9":[a9.offset().left,a9.offset().top],
									"a10":[a10.offset().left,a10.offset().top],
									"a11":[a11.offset().left,a11.offset().top],
									"a12":[a12.offset().left,a12.offset().top],
									"a13":[a13.offset().left,a13.offset().top],
									"a14":[a14.offset().left,a14.offset().top],
									"a15":[a15.offset().left,a15.offset().top],
									"a16":[a16.offset().left,a16.offset().top],
									"a18":[a18.offset().left,a18.offset().top],
									"a20":[a20.offset().left,a20.offset().top],
									"b1":[b1.offset().left,b1.offset().top],
									"b2":[b2.offset().left,b2.offset().top],
									"b5":[b5.offset().left,b5.offset().top]
								} 
			} 
			
			RESULT_JSON.push(personJSON);
			
		}
		
		window.myLine.update(); 

		scrollTo($("#canvasContainer"));

	});

	
	$("#saveData").click(function(){
		console.log(JSON.stringify(RESULT_JSON)); 
		$("#console").text(JSON.stringify(RESULT_JSON));
		$('html, body').stop().animate({'scrollTop': $('#console').offset().top-50}, 500, 'swing');
	});
	
	 
  });


</script>



</body>

</html>