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
<script src="resources/js/ServiceCaller.js"></script> 
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
<!-- Mithilfe dieser App K&ouml;nnen Sie prüfen, wie ähnlich zwei oder mehrere Gesichter zueinander sind. 
Die Grundlage daf&uuml;r bilden sogenannte Gesichtslandmarken. &Uuml;ber diese können korrespondierende Punkte spezifischer Gesichtsmerkmale markiert werden. 
Die Landmarken weisen dann untereinander unterschiedliche Distanzen zueinander auf. 
Die Darstellung unterschiedlicher Distanzverhältnisse kann abschließend einen Überblick darüber verschaffen, inwiefern
unterschiedliche Gesichter, geringe Distanzen und somit große Ähnlichkeiten in Ihren Gesichtsmerkmalen aufweisen.-->
Using this app, you can check how similar two or more faces are to each other. The basis for this form so-called face landmarks. These can be used to mark corresponding points of specific facial features. The landmarks then have mutually different distances to each other. Finally, the depiction of different distances can provide an overview of how different faces, small distances and thus great similarities in your facial features.

</td></tr>
<tr><td style="width: 100px;background: url('resources/images/privacy.png') no-repeat center center transparent;"></td><td>
<!-- Von viel größerer Bedeutung für den Anwender ist das Speichern der in dieser App verarbeiteten Daten.  
An dieser Stelle wird versichert, dass alle Bilder die über eine DropZone o.Ä. für die App zur Verfügung gestellt werden, 
lediglich für die Darstellung im Browser vorgesehen sind, um die Funktion der App zu ermöglichen. 
Eine Speicherung der Bilder im Backend erfolgt somit nicht.
Genau so verhält sich die App im Umgang mit personenspezifischen Positionen einzelner Landmarken.
Sie als Nutzer können frei entscheiden, inwiefern Sie Landmarkenpositionen der Wissenschaft zut Verfügung stellen möchten.
Dabei würden lediglich die Positionen der Landmarken gespeichert und somit keinerlei Informationen dar&uuml;ber, welche auf die Identit&auml;t einer
Person zurückschließen lässt.-->
Of much greater importance to the user is storing the data processed in this app. At this point, it is assured that all images via a DropZone o.Ä. provided for the app, are intended only for display in the browser to enable the function of the app. A storage of images in the backend is thus not. This is exactly how the app behaves when dealing with person-specific positions of individual landmarks. As a user, you are free to decide to what extent you want to provide landmarks to science. In doing so, only the positions of the landmarks would be stored and thus no information about it, which would indicate the identity of a person.

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
<div id="console"></div> 
<button id='saveData' class='myBigButton' style="margin-top: 40px">Save Data</button>

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

			var a1 = $("#a1-"+i);
			var a1Position = (a1.attr("dropped")=="true")?{top:a1.offset().top,left:a1.offset().left}:{top:"undefined",left:"undefined"};

			var a2 = $("#a2-"+i);
			var a2Position = (a2.attr("dropped")=="true")?{top:a2.offset().top,left:a2.offset().left}:{top:"undefined",left:"undefined"};

			var a3 = $("#a3-"+i);
			var a3Position = (a3.attr("dropped")=="true")?{top:a3.offset().top,left:a3.offset().left}:{top:"undefined",left:"undefined"};

			var a4 = $("#a4-"+i);
			var a4Position = (a4.attr("dropped")=="true")?{top:a4.offset().top,left:a4.offset().left}:{top:"undefined",left:"undefined"};

			var a5 = $("#a5-"+i);
			var a5Position = (a5.attr("dropped")=="true")?{top:a5.offset().top,left:a5.offset().left}:{top:"undefined",left:"undefined"};
			
			var a6 = $("#a6-"+i);
			var a6Position = (a6.attr("dropped")=="true")?{top:a6.offset().top,left:a6.offset().left}:{top:"undefined",left:"undefined"};
			
			var a7 = $("#a7-"+i);
			var a7Position = (a7.attr("dropped")=="true")?{top:a7.offset().top,left:a7.offset().left}:{top:"undefined",left:"undefined"};
			
			var a8 = $("#a8-"+i);
			var a8Position = (a8.attr("dropped")=="true")?{top:a8.offset().top,left:a8.offset().left}:{top:"undefined",left:"undefined"};
			
			var a9 = $("#a9-"+i);
			var a9Position = (a9.attr("dropped")=="true")?{top:a9.offset().top,left:a9.offset().left}:{top:"undefined",left:"undefined"};
			
			var a10 = $("#a10-"+i);
			var a10Position = (a10.attr("dropped")=="true")?{top:a10.offset().top,left:a10.offset().left}:{top:"undefined",left:"undefined"};
			
			var a11 = $("#a11-"+i);
			var a11Position = (a11.attr("dropped")=="true")?{top:a11.offset().top,left:a11.offset().left}:{top:"undefined",left:"undefined"};
			
			var a12 = $("#a12-"+i);
			var a12Position = (a12.attr("dropped")=="true")?{top:a12.offset().top,left:a12.offset().left}:{top:"undefined",left:"undefined"};
			
			var a13 = $("#a13-"+i);
			var a13Position = (a13.attr("dropped")=="true")?{top:a13.offset().top,left:a13.offset().left}:{top:"undefined",left:"undefined"};
			
			var a14 = $("#a14-"+i);
			var a14Position = (a14.attr("dropped")=="true")?{top:a14.offset().top,left:a14.offset().left}:{top:"undefined",left:"undefined"};
			
			var a15 = $("#a15-"+i);
			var a15Position = (a15.attr("dropped")=="true")?{top:a15.offset().top,left:a15.offset().left}:{top:"undefined",left:"undefined"};
			
			var a16 = $("#a16-"+i);
			var a16Position = (a16.attr("dropped")=="true")?{top:a16.offset().top,left:a16.offset().left}:{top:"undefined",left:"undefined"};
			
			var a18 = $("#a18-"+i);
			var a18Position = (a18.attr("dropped")=="true")?{top:a18.offset().top,left:a18.offset().left}:{top:"undefined",left:"undefined"};

			var a19 = $("#a19-"+i);
			var a19Position = (a19.attr("dropped")=="true")?{top:a19.offset().top,left:a19.offset().left}:{top:"undefined",left:"undefined"};
			
			var a20 = $("#a20-"+i);			
			var a20Position = (a20.attr("dropped")=="true")?{top:a20.offset().top,left:a20.offset().left}:{top:"undefined",left:"undefined"};

			var a21 = $("#a21-"+i);			
			var a21Position = (a21.attr("dropped")=="true")?{top:a21.offset().top,left:a21.offset().left}:{top:"undefined",left:"undefined"};

			var b1 = $("#b1-"+i);
			var b1Position = (b1.attr("dropped")=="true")?{top:b1.offset().top,left:b1.offset().left}:{top:"undefined",left:"undefined"};
			
			var b2 = $("#b2-"+i);
			var b2Position = (b2.attr("dropped")=="true")?{top:b2.offset().top,left:b2.offset().left}:{top:"undefined",left:"undefined"};

			var b3 = $("#b3-"+i);
			var b3Position = (b3.attr("dropped")=="true")?{top:b3.offset().top,left:b3.offset().left}:{top:"undefined",left:"undefined"};
			
			var b5 = $("#b5-"+i);
			var b5Position = (b5.attr("dropped")=="true")?{top:b5.offset().top,left:b5.offset().left}:{top:"undefined",left:"undefined"};

			var c4 = $("#c4-"+i);
			var c4Position = (c4.attr("dropped")=="true")?{top:c4.offset().top,left:c4.offset().left}:{top:"undefined",left:"undefined"};

			var c5 = $("#c5-"+i);
			var c5Position = (c5.attr("dropped")=="true")?{top:c5.offset().top,left:c5.offset().left}:{top:"undefined",left:"undefined"};
			
			var a6_a11 = euklidDistance(a6Position,a11Position);
			var a7_a10 = euklidDistance(a7Position,a10Position);
			var a8_a9 = euklidDistance(a8Position,a9.offset());
			var a12_a14 = euklidDistance(a12Position,a14Position);
			var a15_a16 = euklidDistance(a15Position,a16Position);
			var a18_a20 = euklidDistance(a18Position,a20Position);
			var a19_a21 = euklidDistance(a19Position,a21Position);
			var b1_a13 = euklidDistance(b1Position,a13Position);			
			var b1_b2 = euklidDistance(b1Position,b2Position);
			var b1_b5 = euklidDistance(b1Position,b5Position);

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

			var a12_a14_a19_a21 = a12_a14 / a19_a21;
			var a15_a16_a19_a21 = a15_a16 / a19_a21; 
			var a6_a11_a19_a21 = a6_a11 / a19_a21;
			
			var a6_a11_a8_a9 = a6_a11 / a8_a9;
			var a7_a10_a8_a9 = a7_a10 / a8_a9;


			window.myLine.data.datasets.push({
				  label: 'Person-' + i, 
				  fill: false,
				  borderColor: colors[i],
				  data: [b1_b2_a6_a11,b1_b5_a6_a11,a12_a14_a15_a16,a12_a14_b1_a13,a12_a14_b1_b5,a15_a16_a6_a11,a15_a16_a7_a10,a8_a9_a15_a16,a8_a9_b1_a13,a8_a9_b1_b5,a7_a10_b1_b5,b1_a13_a7_a10,b1_a13_b1_b5,b1_a13_a6_a11,a12_a14_a18_a20,a15_a16_a18_a20,a6_a11_a18_a20,a12_a14_a19_a21,a15_a16_a19_a21,a6_a11_a19_a21,a6_a11_a8_a9,a7_a10_a8_a9]
			}); 

		 
			var personJSON = {
								landmarks:{
									a1:[a1Position.left,a1Position.top],
									a2:[a2Position.left,a2Position.top],
									a3:[a3Position.left,a3Position.top],
									a4:[a4Position.left,a4Position.top],
									a5:[a5Position.left,a5Position.top],									
									a6:[a6Position.left,a6Position.top],
									a7:[a7Position.left,a7Position.top],
									a8:[a8Position.left,a8Position.top],
									a9:[a9Position.left,a9Position.top],
									a10:[a10Position.left,a10Position.top],
									a11:[a11Position.left,a11Position.top],
									a12:[a12Position.left,a12Position.top],
									a13:[a13Position.left,a13Position.top],
									a14:[a14Position.left,a14Position.top],
									a15:[a15Position.left,a15Position.top],
									a16:[a16Position.left,a16Position.top],
									a18:[a18Position.left,a18Position.top],
									a19:[a19Position.left,a19Position.top],
									a20:[a20Position.left,a20Position.top],
									a21:[a21Position.left,a21Position.top],
									b1:[b1Position.left,b1Position.top],
									b2:[b2Position.left,b2Position.top],
									b3:[b3Position.left,b3Position.top],
									b5:[b5Position.left,b5Position.top],
									c4:[c4Position.left,c4Position.top],
									c5:[c5Position.left,c5Position.top]
								} 
			} 
			
			RESULT_JSON.push(personJSON);
			
		}
		
		window.myLine.update(); 

		console.log(JSON.stringify(RESULT_JSON)); 
		$("#console").text(JSON.stringify(RESULT_JSON));
		$("#saveData").show();

		scrollTo($("#canvasContainer"));

		var showValidationError = false;
		$.each(RESULT_JSON, function (index, data) { 
		    $.each(data, function (index1, data1) {
		    	$.each(data1, function (index2, data2) {
		    		if(data2[0] == "undefined" || data2[1] == "undefined"){
		    			showValidationError = true;
		    		}
		    	});
		    });
		});
		if(showValidationError == true){alert("Not all landmarks has been set!");} 

	});

	
	$("#saveData").click(function(){
		if(confirm("Would you like to make a scientific contribution by saving your set of landmarks?")){
			$.each(RESULT_JSON, function (index, data) { 
				//console.log(JSON.stringify(data));
				ServiceCaller.callService("jsonToFile",{json:JSON.stringify([data])},function(data){
					if(data && data == 1){
						$("#saveData").hide("slow");
					}else{
					  	alert("An unexpected error occurred while saving. Please try again later!");
					}
				});
			});
		}
		//$('html, body').stop().animate({'scrollTop': $('#console').offset().top-50}, 500, 'swing');
	});
	
	 
  });


</script>



</body>

</html>